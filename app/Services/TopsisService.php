<?php

namespace App\Services;

use App\Models\AhpCalculation;
use App\Models\Criterion;
use App\Models\IbuHamil;
use App\Models\Setting;
use App\Models\TopsisResult;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class TopsisService
{
    /**
     * Map clinical data of an Ibu Hamil to Criterion Score (1-4)
     */
    public function getCriterionScores(IbuHamil $ibuHamil): array
    {
        // 1. Anemia (K1)
        // >=11 -> 1; 10-10.9 -> 2; 7-9.9 -> 3; <7 -> 4
        $hb = $ibuHamil->kadar_hb;
        $statusAnemia = strtolower((string) $ibuHamil->status_anemia);
        $scoreAnemia = 1;
        if ($hb !== null && $hb > 0) {
            if ($hb >= 11.0) {
                $scoreAnemia = 1;
            } elseif ($hb >= 10.0) {
                $scoreAnemia = 2;
            } elseif ($hb >= 7.0) {
                $scoreAnemia = 3;
            } else {
                $scoreAnemia = 4;
            }
        } else {
            if (str_contains($statusAnemia, 'berat')) {
                $scoreAnemia = 4;
            } elseif (str_contains($statusAnemia, 'sedang')) {
                $scoreAnemia = 3;
            } elseif (str_contains($statusAnemia, 'ringan')) {
                $scoreAnemia = 2;
            } else {
                $scoreAnemia = 1;
            }
        }

        // 2. IMT (K2)
        // 18.5 - 24.9 -> 1; 25.0 - 29.9 -> 2; 17.0 - 18.4 -> 3; <17.0 or >=30.0 -> 4
        $imt = (float) $ibuHamil->imt;
        $scoreImt = 1;
        if ($imt >= 18.5 && $imt <= 24.99) {
            $scoreImt = 1;
        } elseif ($imt >= 25.0 && $imt <= 29.99) {
            $scoreImt = 2;
        } elseif ($imt >= 17.0 && $imt < 18.5) {
            $scoreImt = 3;
        } elseif ($imt > 0 && ($imt < 17.0 || $imt >= 30.0)) {
            $scoreImt = 4;
        }

        // 3. LILA (K3)
        // >= 23 -> 1; < 23 -> 4
        $lila = (float) $ibuHamil->lila;
        $scoreLila = ($lila > 0 && $lila < 23.0) ? 4 : 1;

        // 4. Usia (K4)
        // 20 - 35 -> 1; < 20 or > 35 -> 4
        $usia = $ibuHamil->usia;
        $scoreUsia = ($usia < 20 || $usia > 35) ? 4 : 1;

        return [
            'K1' => $scoreAnemia,
            'K2' => $scoreImt,
            'K3' => $scoreLila,
            'K4' => $scoreUsia,
        ];
    }

    /**
     * Run complete TOPSIS calculation
     */
    public function calculate(?AhpCalculation $ahpCalc = null, ?Collection $ibuHamilCollection = null): array
    {
        if (!$ahpCalc) {
            $ahpCalc = AhpCalculation::with('weights.criterion')->where('is_active', true)->latest()->first();
        }

        if (!$ahpCalc) {
            throw new \RuntimeException('Belum ada bobot AHP yang aktif. Silakan lakukan perhitungan AHP terlebih dahulu.');
        }

        // Get criterion weights mapped by code
        $weights = [];
        foreach ($ahpCalc->weights as $w) {
            $code = $w->criterion ? $w->criterion->code : null;
            if ($code) {
                $weights[$code] = (float) $w->eigen_vector;
            }
        }

        // Default weights fallback if not found
        $weights['K1'] = $weights['K1'] ?? 0.606;
        $weights['K2'] = $weights['K2'] ?? 0.171;
        $weights['K3'] = $weights['K3'] ?? 0.171;
        $weights['K4'] = $weights['K4'] ?? 0.056;

        $criterionCodes = ['K1', 'K2', 'K3', 'K4'];

        if (!$ibuHamilCollection) {
            $ibuHamilCollection = IbuHamil::all();
        }

        if ($ibuHamilCollection->isEmpty()) {
            return [
                'hasData' => false,
                'message' => 'Belum ada data ibu hamil untuk dihitung.',
            ];
        }

        // 1. Build Decision Matrix (X)
        $decisionMatrix = [];
        $rawScores = [];
        foreach ($ibuHamilCollection as $item) {
            $scores = $this->getCriterionScores($item);
            $rawScores[$item->id] = $scores;
            foreach ($criterionCodes as $code) {
                $decisionMatrix[$item->id][$code] = (float) $scores[$code];
            }
        }

        // 2. Normalization Divisors: S_j = sqrt(sum(x_ij^2))
        $divisors = [];
        foreach ($criterionCodes as $code) {
            $sumSquares = 0;
            foreach ($ibuHamilCollection as $item) {
                $val = $decisionMatrix[$item->id][$code];
                $sumSquares += ($val * $val);
            }
            $divisors[$code] = sqrt($sumSquares);
        }

        // 3. Normalized Matrix (R) & Weighted Normalized Matrix (V)
        $normalizedMatrix = [];
        $weightedMatrix = [];
        foreach ($ibuHamilCollection as $item) {
            foreach ($criterionCodes as $code) {
                $div = $divisors[$code] > 0 ? $divisors[$code] : 1;
                $r_ij = $decisionMatrix[$item->id][$code] / $div;
                $v_ij = $r_ij * $weights[$code];

                $normalizedMatrix[$item->id][$code] = $r_ij;
                $weightedMatrix[$item->id][$code] = $v_ij;
            }
        }

        // 4. Positive Ideal (A+) and Negative Ideal (A-)
        // All criteria are benefit (risk scores)
        $idealPositive = [];
        $idealNegative = [];
        foreach ($criterionCodes as $code) {
            $columnValues = [];
            foreach ($ibuHamilCollection as $item) {
                $columnValues[] = $weightedMatrix[$item->id][$code];
            }
            $idealPositive[$code] = !empty($columnValues) ? max($columnValues) : 0;
            $idealNegative[$code] = !empty($columnValues) ? min($columnValues) : 0;
        }

        // 5. Euclidean Distances (D+ and D-)
        $dPlus = [];
        $dMinus = [];
        $closeness = [];
        foreach ($ibuHamilCollection as $item) {
            $sumDPlus = 0;
            $sumDMinus = 0;

            foreach ($criterionCodes as $code) {
                $v = $weightedMatrix[$item->id][$code];
                $diffPlus = $v - $idealPositive[$code];
                $diffMinus = $v - $idealNegative[$code];

                $sumDPlus += ($diffPlus * $diffPlus);
                $sumDMinus += ($diffMinus * $diffMinus);
            }

            $dPlusVal = sqrt($sumDPlus);
            $dMinusVal = sqrt($sumDMinus);

            $dPlus[$item->id] = $dPlusVal;
            $dMinus[$item->id] = $dMinusVal;

            $totalDist = $dPlusVal + $dMinusVal;
            $v_i = $totalDist > 0 ? ($dMinusVal / $totalDist) : 0;
            $closeness[$item->id] = $v_i;
        }

        // 6. Ranking
        arsort($closeness);

        $thresholdHigh = (float) Setting::get('threshold_high', '0.80');
        $thresholdMedium = (float) Setting::get('threshold_medium', '0.60');

        $rank = 1;
        $results = [];
        foreach ($closeness as $ibuHamilId => $prefScore) {
            $ibuHamil = $ibuHamilCollection->firstWhere('id', $ibuHamilId);
            $scores = $rawScores[$ibuHamilId];

            // Priority categorization
            if ($prefScore >= $thresholdHigh) {
                $priority = 'Tinggi';
            } elseif ($prefScore >= $thresholdMedium) {
                $priority = 'Sedang';
            } else {
                $priority = 'Rendah';
            }

            // Main risk factors
            $riskFactors = [];
            if ($scores['K1'] >= 3) {
                $riskFactors[] = 'Anemia';
            }
            if ($scores['K3'] >= 4) {
                $riskFactors[] = 'LILA';
            }
            if ($scores['K2'] >= 3) {
                $riskFactors[] = 'IMT';
            }
            if ($scores['K4'] >= 4) {
                $riskFactors[] = 'Usia Kehamilan/Reproduksi';
            }

            $riskFactorsText = !empty($riskFactors) ? implode(', ', $riskFactors) : '-';

            // Recommendations
            $recs = [];
            if ($scores['K1'] >= 3) {
                $recs[] = 'Pemberian tablet tambah darah';
            }
            if ($scores['K3'] >= 4) {
                $recs[] = 'Pemberian makanan tambahan (PMT)';
            }
            if ($scores['K2'] >= 3) {
                $recs[] = 'Konseling gizi seimbang';
            }
            if ($scores['K4'] >= 4) {
                $recs[] = 'Pemeriksaan kehamilan terpadu';
            }
            if (empty($recs)) {
                $recs[] = 'Pemantauan rutin dan edukasi kehamilan';
            }

            $recommendationText = implode(' & ', array_slice($recs, 0, 2));

            $results[] = [
                'rank' => $rank++,
                'ibu_hamil_id' => $ibuHamilId,
                'ibu_hamil' => $ibuHamil,
                'raw_scores' => $scores,
                'score_anemia' => $scores['K1'],
                'score_imt' => $scores['K2'],
                'score_lila' => $scores['K3'],
                'score_usia' => $scores['K4'],
                'd_plus' => $dPlus[$ibuHamilId],
                'd_minus' => $dMinus[$ibuHamilId],
                'preference_score' => $prefScore,
                'priority' => $priority,
                'main_risk_factors' => $riskFactorsText,
                'recommendation' => $recommendationText,
            ];
        }

        return [
            'hasData' => true,
            'ahpCalculation' => $ahpCalc,
            'weights' => $weights,
            'criterionCodes' => $criterionCodes,
            'ibuHamilCollection' => $ibuHamilCollection,
            'decisionMatrix' => $decisionMatrix,
            'divisors' => $divisors,
            'normalizedMatrix' => $normalizedMatrix,
            'weightedMatrix' => $weightedMatrix,
            'idealPositive' => $idealPositive,
            'idealNegative' => $idealNegative,
            'dPlus' => $dPlus,
            'dMinus' => $dMinus,
            'results' => $results,
        ];
    }

    /**
     * Save TOPSIS results to database
     */
    public function saveResults(array $calculationResult): void
    {
        if (empty($calculationResult['results'])) {
            return;
        }

        DB::transaction(function () use ($calculationResult) {
            $ahpId = $calculationResult['ahpCalculation'] ? $calculationResult['ahpCalculation']->id : null;

            foreach ($calculationResult['results'] as $row) {
                TopsisResult::updateOrCreate(
                    ['ibu_hamil_id' => $row['ibu_hamil_id']],
                    [
                        'ahp_calculation_id' => $ahpId,
                        'score_anemia' => $row['score_anemia'],
                        'score_imt' => $row['score_imt'],
                        'score_lila' => $row['score_lila'],
                        'score_usia' => $row['score_usia'],
                        'd_plus' => $row['d_plus'],
                        'd_minus' => $row['d_minus'],
                        'preference_score' => $row['preference_score'],
                        'rank' => $row['rank'],
                        'priority' => $row['priority'],
                        'main_risk_factors' => $row['main_risk_factors'],
                        'recommendation' => $row['recommendation'],
                    ]
                );
            }
        });
    }
}
