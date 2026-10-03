<?php

namespace App\Livewire\Ahp;

use App\Models\AhpCalculation;
use App\Models\Criterion;
use App\Services\AhpService;
use Livewire\Component;

class Comparison extends Component
{
    // List of pairwise inputs: key "id1_id2" => ['dominant' => id, 'scale' => 1..9]
    public array $pairwise = [];
    public ?array $calculationResult = null;
    public ?AhpCalculation $savedCalculation = null;

    public function mount(AhpService $ahpService)
    {
        $this->savedCalculation = $ahpService->getActiveCalculation();
        $this->initializePairs();
    }

    public function initializePairs()
    {
        $criteria = Criterion::orderBy('code')->get();
        $n = $criteria->count();

        // Load existing comparisons if available
        $existingComparisons = [];
        if ($this->savedCalculation) {
            foreach ($this->savedCalculation->comparisons as $comp) {
                $existingComparisons[$comp->criterion1_id . '_' . $comp->criterion2_id] = (float) $comp->value;
            }
        }

        for ($i = 0; $i < $n; $i++) {
            for ($j = $i + 1; $j < $n; $j++) {
                $c1 = $criteria[$i];
                $c2 = $criteria[$j];
                $key = $c1->id . '_' . $c2->id;

                if (isset($existingComparisons[$key])) {
                    $val = $existingComparisons[$key];
                    if ($val >= 1.0) {
                        $this->pairwise[$key] = [
                            'dominant' => $c1->id,
                            'scale' => (int) round($val),
                        ];
                    } else {
                        $this->pairwise[$key] = [
                            'dominant' => $c2->id,
                            'scale' => (int) round(1 / $val),
                        ];
                    }
                } else {
                    // Default values matching PDF page 7
                    // K1 vs K2 = 3; K1 vs K3 = 5; K1 vs K4 = 2; K2 vs K3 = 2; K2 vs K4 = 0.5 (K4 is 2); K3 vs K4 = 0.33 (K4 is 3)
                    $defaultDominant = $c1->id;
                    $defaultScale = 1;

                    if ($c1->code === 'K1' && $c2->code === 'K2') { $defaultDominant = $c1->id; $defaultScale = 3; }
                    elseif ($c1->code === 'K1' && $c2->code === 'K3') { $defaultDominant = $c1->id; $defaultScale = 5; }
                    elseif ($c1->code === 'K1' && $c2->code === 'K4') { $defaultDominant = $c1->id; $defaultScale = 2; }
                    elseif ($c1->code === 'K2' && $c2->code === 'K3') { $defaultDominant = $c1->id; $defaultScale = 2; }
                    elseif ($c1->code === 'K2' && $c2->code === 'K4') { $defaultDominant = $c2->id; $defaultScale = 2; }
                    elseif ($c1->code === 'K3' && $c2->code === 'K4') { $defaultDominant = $c2->id; $defaultScale = 3; }

                    $this->pairwise[$key] = [
                        'dominant' => $defaultDominant,
                        'scale' => $defaultScale,
                    ];
                }
            }
        }
    }

    public function calculate(AhpService $ahpService)
    {
        $criteria = Criterion::orderBy('code')->get();
        $n = $criteria->count();

        if ($n < 2) {
            session()->flash('error', 'Minimal diperlukan 2 kriteria untuk melakukan perhitungan AHP.');
            return;
        }

        // Build 2D square matrix
        $matrix = [];
        foreach ($criteria as $cRow) {
            foreach ($criteria as $cCol) {
                if ($cRow->id === $cCol->id) {
                    $matrix[$cRow->id][$cCol->id] = 1.0;
                } else {
                    $key = $cRow->id < $cCol->id ? ($cRow->id . '_' . $cCol->id) : ($cCol->id . '_' . $cRow->id);
                    $pair = $this->pairwise[$key] ?? ['dominant' => $cRow->id, 'scale' => 1];
                    $scale = max(1, (int) $pair['scale']);

                    if ($cRow->id < $cCol->id) {
                        $val = ($pair['dominant'] == $cRow->id) ? (float)$scale : (1.0 / $scale);
                    } else {
                        $val = ($pair['dominant'] == $cCol->id) ? (1.0 / $scale) : (float)$scale;
                    }

                    $matrix[$cRow->id][$cCol->id] = $val;
                }
            }
        }

        $result = $ahpService->calculate($matrix, $criteria->pluck('id')->toArray());
        $this->calculationResult = $result;

        // Flatten pairwise inputs for saving
        $pairwiseInputs = [];
        for ($i = 0; $i < $n; $i++) {
            for ($j = $i + 1; $j < $n; $j++) {
                $c1 = $criteria[$i];
                $c2 = $criteria[$j];
                $val = $matrix[$c1->id][$c2->id];
                $pairwiseInputs[] = [
                    'criterion1_id' => $c1->id,
                    'criterion2_id' => $c2->id,
                    'value' => $val,
                ];
            }
        }

        $userId = auth()->id();
        $this->savedCalculation = $ahpService->saveCalculation($result, $pairwiseInputs, $userId);

        if ($result['isConsistent']) {
            session()->flash('success', 'Perhitungan AHP selesai. Matriks dinyatakan konsisten (CR = ' . $result['cr'] . ' ≤ 0.10) dan bobot tersimpan.');
        } else {
            session()->flash('error', 'Perhitungan AHP selesai, namun perbandingan dinyatakan tidak konsisten (CR = ' . $result['cr'] . ' > 0.10). Silakan tinjau kembali nilai perbandingan.');
        }
    }

    public function resetPairs()
    {
        $this->initializePairs();
        $this->calculationResult = null;
        session()->flash('success', 'Nilai perbandingan telah diatur ulang ke posisi awal.');
    }

    public function render()
    {
        $criteria = Criterion::orderBy('code')->get();

        return view('livewire.ahp.comparison', [
            'criteria' => $criteria,
        ])->layout('layouts.app', [
            'title' => 'Perhitungan Bobot (AHP)',
            'breadcrumb' => 'Perhitungan AHP',
        ]);
    }
}
