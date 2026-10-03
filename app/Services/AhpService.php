<?php

namespace App\Services;

use App\Models\AhpCalculation;
use App\Models\AhpComparison;
use App\Models\AhpWeight;
use App\Models\Criterion;
use Illuminate\Support\Facades\DB;

class AhpService
{
    /**
     * Random Index (RI) table by Thomas L. Saaty
     */
    protected array $randomIndexTable = [
        1 => 0.00,
        2 => 0.00,
        3 => 0.58,
        4 => 0.90,
        5 => 1.12,
        6 => 1.24,
        7 => 1.32,
        8 => 1.41,
        9 => 1.45,
        10 => 1.49,
    ];

    /**
     * Get Random Index for n criteria
     */
    public function getRandomIndex(int $n): float
    {
        return $this->randomIndexTable[$n] ?? 1.49;
    }

    /**
     * Calculate AHP from pairwise matrix
     * 
     * @param array $matrix 2D square matrix [criterion_id => [criterion_id => value]]
     * @param array $criteria List of Criterion models or ids
     * @return array Contains matrix, colSums, normalizedMatrix, rowSums, weights, lambdaMax, ci, cr, isConsistent, RI
     */
    public function calculate(array $matrix, array $criteria): array
    {
        $n = count($criteria);
        $criteriaIds = array_keys($matrix);

        // 1. Column sums
        $colSums = [];
        foreach ($criteriaIds as $colId) {
            $sum = 0;
            foreach ($criteriaIds as $rowId) {
                $sum += (float) ($matrix[$rowId][$colId] ?? 1);
            }
            $colSums[$colId] = $sum;
        }

        // 2. Normalization
        $normalizedMatrix = [];
        $rowSums = [];
        foreach ($criteriaIds as $rowId) {
            $rowSum = 0;
            foreach ($criteriaIds as $colId) {
                $val = (float) ($matrix[$rowId][$colId] ?? 1);
                $normVal = $colSums[$colId] > 0 ? ($val / $colSums[$colId]) : 0;
                $normalizedMatrix[$rowId][$colId] = $normVal;
                $rowSum += $normVal;
            }
            $rowSums[$rowId] = $rowSum;
        }

        // 3. Priority Weights (Eigen Vector)
        $weights = [];
        $weightPercentages = [];
        foreach ($criteriaIds as $rowId) {
            $eigenVector = $n > 0 ? ($rowSums[$rowId] / $n) : 0;
            $weights[$rowId] = round($eigenVector, 4);
            $weightPercentages[$rowId] = round($eigenVector * 100, 1);
        }

        // 4. Lambda Max
        // A * w = y
        $lambdaTotal = 0;
        $lambdaList = [];
        foreach ($criteriaIds as $rowId) {
            $y_i = 0;
            foreach ($criteriaIds as $colId) {
                $y_i += ((float) ($matrix[$rowId][$colId] ?? 1)) * $weights[$colId];
            }
            $lambda_i = $weights[$rowId] > 0 ? ($y_i / $weights[$rowId]) : 0;
            $lambdaList[$rowId] = $lambda_i;
            $lambdaTotal += $lambda_i;
        }
        $lambdaMax = $n > 0 ? ($lambdaTotal / $n) : 0;

        // 5. Consistency Index (CI)
        $ci = $n > 1 ? (($lambdaMax - $n) / ($n - 1)) : 0;
        if ($ci < 0) {
            $ci = 0; // Guard against slight floating point inaccuracy
        }

        // 6. Consistency Ratio (CR)
        $ri = $this->getRandomIndex($n);
        $cr = $ri > 0 ? ($ci / $ri) : 0;

        // 7. Consistency Status (CR <= 0.10)
        $isConsistent = round($cr, 3) <= 0.10;

        return [
            'n' => $n,
            'criteriaIds' => $criteriaIds,
            'matrix' => $matrix,
            'colSums' => $colSums,
            'normalizedMatrix' => $normalizedMatrix,
            'rowSums' => $rowSums,
            'weights' => $weights,
            'weightPercentages' => $weightPercentages,
            'lambdaList' => $lambdaList,
            'lambdaMax' => round($lambdaMax, 4),
            'ci' => round($ci, 4),
            'ri' => $ri,
            'cr' => round($cr, 4),
            'isConsistent' => $isConsistent,
        ];
    }

    /**
     * Save calculation result to database
     */
    public function saveCalculation(array $result, array $pairwiseInputs, ?int $userId = null): AhpCalculation
    {
        return DB::transaction(function () use ($result, $pairwiseInputs, $userId) {
            // Deactivate previous calculations
            AhpCalculation::where('is_active', true)->update(['is_active' => false]);

            $calculation = AhpCalculation::create([
                'lambda_max' => $result['lambdaMax'],
                'ci' => $result['ci'],
                'cr' => $result['cr'],
                'is_consistent' => $result['isConsistent'],
                'is_active' => true,
                'created_by' => $userId,
            ]);

            // Save weights
            foreach ($result['weights'] as $criterionId => $weight) {
                AhpWeight::create([
                    'ahp_calculation_id' => $calculation->id,
                    'criterion_id' => $criterionId,
                    'eigen_vector' => $weight,
                    'weight_percentage' => $result['weightPercentages'][$criterionId] ?? ($weight * 100),
                ]);
            }

            // Save comparisons
            foreach ($pairwiseInputs as $pair) {
                AhpComparison::create([
                    'ahp_calculation_id' => $calculation->id,
                    'criterion1_id' => $pair['criterion1_id'],
                    'criterion2_id' => $pair['criterion2_id'],
                    'value' => $pair['value'],
                ]);
            }

            return $calculation;
        });
    }

    /**
     * Get active AHP calculation or return null
     */
    public function getActiveCalculation(): ?AhpCalculation
    {
        return AhpCalculation::with(['weights.criterion', 'comparisons'])->where('is_active', true)->latest()->first();
    }
}
