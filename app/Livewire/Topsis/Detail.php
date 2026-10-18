<?php

namespace App\Livewire\Topsis;

use App\Models\AhpCalculation;
use App\Models\IbuHamil;
use App\Services\TopsisService;
use Livewire\Component;

class Detail extends Component
{
    public array $topsisData = [];
    public bool $showStep2Formula = false;
    public bool $showStep3Formula = false;
    public bool $showStep4Formula = false;
    public bool $showStep5Formula = false;
    public bool $showStep6Formula = false;

    public function mount(TopsisService $topsisService)
    {
        $activeAhp = AhpCalculation::with('weights.criterion')->where('is_active', true)->latest()->first();
        if ($activeAhp) {
            $this->topsisData = $topsisService->calculate($activeAhp);
        }
    }

    public function toggleStep(int $step)
    {
        if ($step === 2) $this->showStep2Formula = !$this->showStep2Formula;
        if ($step === 3) $this->showStep3Formula = !$this->showStep3Formula;
        if ($step === 4) $this->showStep4Formula = !$this->showStep4Formula;
        if ($step === 5) $this->showStep5Formula = !$this->showStep5Formula;
        if ($step === 6) $this->showStep6Formula = !$this->showStep6Formula;
    }

    public function toggleFormula()
    {
        $this->showStep2Formula = !$this->showStep2Formula;
    }

    public function render()
    {
        $criteria = \App\Models\Criterion::orderBy('code')->get();

        return view('livewire.topsis.detail', [
            'criteria' => $criteria,
        ])->layout('layouts.app', [
            'title' => 'Detail Perhitungan TOPSIS',
            'breadcrumb' => 'Detail Perhitungan TOPSIS',
        ]);
    }
}
