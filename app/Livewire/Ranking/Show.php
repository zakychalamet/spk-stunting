<?php

namespace App\Livewire\Ranking;

use App\Models\TopsisResult;
use Livewire\Component;

class Show extends Component
{
    public TopsisResult $topsisResult;
    public int $totalPatients = 0;

    public function mount(int $id)
    {
        $this->topsisResult = TopsisResult::with(['ibuHamil', 'ahpCalculation.weights.criterion'])->findOrFail($id);
        $this->totalPatients = TopsisResult::count();
    }

    public function render()
    {
        return view('livewire.ranking.show')->layout('layouts.app', [
            'title' => 'Detail Hasil Perangkingan',
            'breadcrumb' => 'Detail Perangkingan',
        ]);
    }
}
