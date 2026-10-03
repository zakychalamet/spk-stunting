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

    public function mount(TopsisService $topsisService)
    {
        $activeAhp = AhpCalculation::with('weights.criterion')->where('is_active', true)->latest()->first();
        if ($activeAhp) {
            $this->topsisData = $topsisService->calculate($activeAhp);
        }
    }

    public function toggleFormula()
    {
        $this->showStep2Formula = !$this->showStep2Formula;
    }

    public function render()
    {
        return view('livewire.topsis.detail')->layout('layouts.app', [
            'title' => 'Detail Perhitungan TOPSIS',
            'breadcrumb' => 'Detail Perhitungan TOPSIS',
        ]);
    }
}
