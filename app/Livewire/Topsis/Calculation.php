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
    public ?array $notification = null;

    public function mount()
    {
        $this->activeAhp = AhpCalculation::with('weights.criterion')->where('is_active', true)->latest()->first();
        $this->loadStats();
    }

    public function loadStats()
    {
        $this->totalIbuHamil = IbuHamil::count();
        $activeId = $this->activeAhp?->id;

        $query = TopsisResult::whereHas('ibuHamil');
        if ($activeId) {
            $query->where('ahp_calculation_id', $activeId);
        }

        $this->countTinggi = (clone $query)->where('priority', 'Tinggi')->count();
        $this->countSedang = (clone $query)->where('priority', 'Sedang')->count();
        $this->countRendah = (clone $query)->where('priority', 'Rendah')->count();
    }

    public function runCalculation(TopsisService $topsisService)
    {
        if (!$this->activeAhp) {
            $msg = 'Belum ada bobot AHP yang aktif. Silakan lakukan perhitungan AHP terlebih dahulu.';
            session()->flash('error', $msg);
            $this->notification = [
                'type' => 'error',
                'title' => 'Perhitungan TOPSIS Gagal',
                'message' => $msg,
            ];
            return redirect()->route('ahp.index');
        }

        try {
            $calculation = $topsisService->calculate($this->activeAhp);

            if (!$calculation['hasData']) {
                session()->flash('error', $calculation['message']);
                $this->notification = [
                    'type' => 'error',
                    'title' => 'Data Tidak Cukup',
                    'message' => $calculation['message'],
                ];
                return;
            }

            $topsisService->saveResults($calculation);
            $this->loadStats();

            $msg = 'Perhitungan metode TOPSIS berhasil dieksekusi. Hasil perangkingan prioritas ibu hamil telah diperbarui.';
            session()->flash('success', $msg);
            $this->notification = [
                'type' => 'success',
                'title' => 'TOPSIS Berhasil Dihitung!',
                'message' => $msg,
            ];
        } catch (\Throwable $e) {
            $msg = 'Terjadi kesalahan saat menghitung TOPSIS: ' . $e->getMessage();
            session()->flash('error', $msg);
            $this->notification = [
                'type' => 'error',
                'title' => 'Terjadi Kesalahan',
                'message' => $msg,
            ];
        }
    }

    public function dismissNotification()
    {
        $this->notification = null;
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
