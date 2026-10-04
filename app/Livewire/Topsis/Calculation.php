<?php

namespace App\Livewire\Topsis;

use App\Models\AhpCalculation;
use App\Models\IbuHamil;
use App\Models\TopsisResult;
use App\Services\TopsisService;
use Livewire\Component;

class Calculation extends Component
{
    public ?AhpCalculation $activeAhp = null;
    public int $totalIbuHamil = 0;
    public int $countTinggi = 0;
    public int $countSedang = 0;
    public int $countRendah = 0;

    public string $search = '';
    public string $filterPriority = '';

    public function mount()
    {
        $this->activeAhp = AhpCalculation::with('weights.criterion')->where('is_active', true)->latest()->first();
        $this->loadStats();
    }

    public function loadStats()
    {
        $this->totalIbuHamil = IbuHamil::count();
        $this->countTinggi = TopsisResult::where('priority', 'Tinggi')->count();
        $this->countSedang = TopsisResult::where('priority', 'Sedang')->count();
        $this->countRendah = TopsisResult::where('priority', 'Rendah')->count();
    }

    public function runCalculation(TopsisService $topsisService)
    {
        if (!$this->activeAhp) {
            session()->flash('error', 'Belum ada bobot AHP yang aktif. Silakan lakukan perhitungan AHP terlebih dahulu.');
            return redirect()->route('ahp.index');
        }

        try {
            $calculation = $topsisService->calculate($this->activeAhp);

            if (!$calculation['hasData']) {
                session()->flash('error', $calculation['message']);
                return;
            }

            $topsisService->saveResults($calculation);
            $this->loadStats();

            session()->flash('success', 'Perhitungan metode TOPSIS berhasil dieksekusi. Hasil perangkingan telah diperbarui.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Terjadi kesalahan saat menghitung TOPSIS: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = TopsisResult::with('ibuHamil')->orderBy('rank', 'asc');

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

        return view('livewire.topsis.calculation', [
            'results' => $results,
        ])->layout('layouts.app', [
            'title' => 'Perhitungan TOPSIS',
            'breadcrumb' => 'Perhitungan TOPSIS',
        ]);
    }
}
