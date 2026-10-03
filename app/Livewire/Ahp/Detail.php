<?php

namespace App\Livewire\Ahp;

use App\Models\AhpCalculation;
use App\Models\Criterion;
use App\Services\AhpService;
use Livewire\Component;

class Detail extends Component
{
    public ?AhpCalculation $calculation = null;
    public array $detailData = [];
    public bool $showStep1 = false;
    public bool $showStep2 = false;
    public bool $showStep3 = false;
    public bool $showStep4 = false;

    public function mount(AhpService $ahpService)
    {
        $this->calculation = $ahpService->getActiveCalculation();

        if ($this->calculation) {
            $criteria = Criterion::orderBy('code')->get();
            $matrix = [];

            // Initialize identity
            foreach ($criteria as $r) {
                foreach ($criteria as $c) {
                    $matrix[$r->id][$c->id] = ($r->id == $c->id) ? 1.0 : 1.0;
                }
            }

            // Fill from comparisons
            foreach ($this->calculation->comparisons as $comp) {
                $val = (float) $comp->value;
                $matrix[$comp->criterion1_id][$comp->criterion2_id] = $val;
                $matrix[$comp->criterion2_id][$comp->criterion1_id] = $val > 0 ? (1.0 / $val) : 1.0;
            }

            $this->detailData = $ahpService->calculate($matrix, $criteria->pluck('id')->toArray());
        }
    }

    public function toggleStep(int $step)
    {
        if ($step === 1) $this->showStep1 = !$this->showStep1;
        if ($step === 2) $this->showStep2 = !$this->showStep2;
        if ($step === 3) $this->showStep3 = !$this->showStep3;
        if ($step === 4) $this->showStep4 = !$this->showStep4;
    }

    public function render()
    {
        $criteria = Criterion::orderBy('code')->get();

        return view('livewire.ahp.detail', [
            'criteria' => $criteria,
        ])->layout('layouts.app', [
            'title' => 'Detail Perhitungan AHP',
            'breadcrumb' => 'Detail Perhitungan AHP',
        ]);
    }
}
