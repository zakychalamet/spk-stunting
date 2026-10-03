<?php

namespace App\Livewire\Ranking;

use App\Models\TopsisResult;
use Livewire\Component;

class Index extends Component
{
    public string $search = '';
    public string $filterPriority = '';

    public function render()
    {
        $query = TopsisResult::with(['ibuHamil', 'ahpCalculation'])->orderBy('rank', 'asc');

        if (!empty($this->search)) {
            $query->whereHas('ibuHamil', function ($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                  ->orWhere('kode_ibu_hamil', 'like', '%' . $this->search . '%')
                  ->orWhere('desa_kelurahan', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->filterPriority)) {
            $query->where('priority', $this->filterPriority);
        }

        $results = $query->get();

        return view('livewire.ranking.index', [
            'results' => $results,
        ])->layout('layouts.app', [
            'title' => 'Hasil Perangkingan',
            'breadcrumb' => 'Hasil Perangkingan',
        ]);
    }
}
