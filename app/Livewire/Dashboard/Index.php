<?php

namespace App\Livewire\Dashboard;

use App\Models\AhpCalculation;
use App\Models\IbuHamil;
use App\Models\TopsisResult;
use Carbon\Carbon;
use Livewire\Component;

class Index extends Component
{
    public string $activeChart = 'anemia';

    public function setChart(string $chart)
    {
        if (in_array($chart, ['anemia', 'lila', 'imt', 'usia'])) {
            $this->activeChart = $chart;
        }
    }

    public function nextChart()
    {
        $charts = ['anemia', 'lila', 'imt', 'usia'];
        $currentIndex = array_search($this->activeChart, $charts);
        $nextIndex = ($currentIndex + 1) % count($charts);
        $this->activeChart = $charts[$nextIndex];
    }

    public function prevChart()
    {
        $charts = ['anemia', 'lila', 'imt', 'usia'];
        $currentIndex = array_search($this->activeChart, $charts);
        $prevIndex = ($currentIndex - 1 + count($charts)) % count($charts);
        $this->activeChart = $charts[$prevIndex];
    }

    public function render()
    {
        $totalIbuHamil = IbuHamil::count();
        $activeAhp = AhpCalculation::where('is_active', true)->latest()->first();
        $activeAhpId = $activeAhp?->id;

        $topsisQuery = TopsisResult::whereHas('ibuHamil');
        if ($activeAhpId) {
            $topsisQuery->where('ahp_calculation_id', $activeAhpId);
        }

        $countPriorityTinggi = (clone $topsisQuery)->where('priority', 'Tinggi')->count();
        $countPrioritySedang = (clone $topsisQuery)->where('priority', 'Sedang')->count();
        $countPriorityRendah = (clone $topsisQuery)->where('priority', 'Rendah')->count();

        // Top 5 Highest Priority Patients
        $topPriorities = (clone $topsisQuery)
            ->with('ibuHamil')
            ->orderBy('rank', 'asc')
            ->take(5)
            ->get();

        // Greeting based on current time (WIB / Asia/Jakarta)
        $hour = Carbon::now('Asia/Jakarta')->hour;
        if ($hour >= 5 && $hour <= 10) {
            $greeting = 'Selamat Pagi';
        } elseif ($hour >= 11 && $hour <= 14) {
            $greeting = 'Selamat Siang';
        } elseif ($hour >= 15 && $hour <= 17) {
            $greeting = 'Selamat Sore';
        } else {
            $greeting = 'Selamat Malam';
        }

        // Charts configuration
        $allIbu = IbuHamil::all();

        // 1. Anemia Data (Sesuai rentang status anemia data ibu hamil)
        $anemiaNormal = $allIbu->filter(fn($i) => $i->status_anemia_label === 'Normal (≥ 11)')->count();
        $anemiaRingan = $allIbu->filter(fn($i) => $i->status_anemia_label === 'Ringan (10 - 10,9)')->count();
        $anemiaSedang = $allIbu->filter(fn($i) => $i->status_anemia_label === 'Sedang (8 - 9,9)')->count();
        $anemiaBerat = $allIbu->filter(fn($i) => $i->status_anemia_label === 'Berat (< 8)')->count();

        $anemiaData = [
            [
                'label' => 'Normal (≥ 11)',
                'legend' => 'Normal (≥ 11)',
                'count' => $anemiaNormal,
                'color' => 'bg-emerald-300 hover:bg-emerald-400',
                'dot_color' => 'bg-emerald-300',
                'hex' => '#6ee7b7',
            ],
            [
                'label' => 'Ringan (10 - 10,9)',
                'legend' => 'Ringan (10 - 10,9)',
                'count' => $anemiaRingan,
                'color' => 'bg-emerald-700 hover:bg-emerald-800',
                'dot_color' => 'bg-emerald-700',
                'hex' => '#047857',
            ],
            [
                'label' => 'Sedang (8 - 9,9)',
                'legend' => 'Sedang (8 - 9,9)',
                'count' => $anemiaSedang,
                'color' => 'bg-amber-600 hover:bg-amber-700',
                'dot_color' => 'bg-amber-600',
                'hex' => '#d97706',
            ],
            [
                'label' => 'Berat (< 8)',
                'legend' => 'Berat (< 8)',
                'count' => $anemiaBerat,
                'color' => 'bg-red-500 hover:bg-red-600',
                'dot_color' => 'bg-red-500',
                'hex' => '#ef4444',
            ],
        ];

        // 2. LILA Data
        $lilaNormal = $allIbu->filter(fn($i) => (float)$i->lila >= 23.5)->count();
        $lilaKek = $allIbu->filter(fn($i) => (float)$i->lila > 0 && (float)$i->lila < 23.5)->count();
        $lilaData = [
            [
                'label' => 'Normal (≥ 23.5 cm)',
                'legend' => 'Normal (≥ 23.5 cm)',
                'count' => $lilaNormal,
                'color' => 'bg-emerald-600 hover:bg-emerald-700',
                'dot_color' => 'bg-emerald-600',
                'hex' => '#059669',
            ],
            [
                'label' => 'KEK (< 23.5 cm)',
                'legend' => 'Risiko KEK (< 23.5 cm)',
                'count' => $lilaKek,
                'color' => 'bg-red-500 hover:bg-red-600',
                'dot_color' => 'bg-red-500',
                'hex' => '#ef4444',
            ],
        ];

        // 3. IMT Data (Sesuai K2: 18.5 - 24.9, 25.0 - 29.9, >= 30, < 18.5)
        $imtNormal = $allIbu->filter(fn($i) => (float)$i->imt >= 18.5 && (float)$i->imt < 25.0)->count();
        $imtLebih = $allIbu->filter(fn($i) => (float)$i->imt >= 25.0 && (float)$i->imt < 30.0)->count();
        $imtObesitas = $allIbu->filter(fn($i) => (float)$i->imt >= 30.0)->count();
        $imtKurus = $allIbu->filter(fn($i) => (float)$i->imt > 0 && (float)$i->imt < 18.5)->count();
        $imtData = [
            [
                'label' => 'Normal (18.5 - 24.9)',
                'legend' => 'Normal (18.5 - 24.9)',
                'count' => $imtNormal,
                'color' => 'bg-emerald-500 hover:bg-emerald-600',
                'dot_color' => 'bg-emerald-500',
                'hex' => '#10b981',
            ],
            [
                'label' => 'BB Berlebih (25.0 - 29.9)',
                'legend' => 'BB Berlebih (25.0 - 29.9)',
                'count' => $imtLebih,
                'color' => 'bg-amber-500 hover:bg-amber-600',
                'dot_color' => 'bg-amber-500',
                'hex' => '#f59e0b',
            ],
            [
                'label' => 'Obesitas (≥ 30)',
                'legend' => 'Obesitas (≥ 30)',
                'count' => $imtObesitas,
                'color' => 'bg-amber-600 hover:bg-amber-700',
                'dot_color' => 'bg-amber-600',
                'hex' => '#d97706',
            ],
            [
                'label' => 'Kurus (< 18.5)',
                'legend' => 'Kurus (< 18.5)',
                'count' => $imtKurus,
                'color' => 'bg-red-500 hover:bg-red-600',
                'dot_color' => 'bg-red-500',
                'hex' => '#ef4444',
            ],
        ];

        // 4. Usia Data
        $usiaIdeal = $allIbu->filter(fn($i) => $i->usia >= 20 && $i->usia <= 35)->count();
        $usiaRisiko = $allIbu->filter(fn($i) => $i->usia > 0 && ($i->usia < 20 || $i->usia > 35))->count();
        $usiaData = [
            [
                'label' => 'Usia Ideal (20 - 35 th)',
                'legend' => 'Ideal (20 - 35 th)',
                'count' => $usiaIdeal,
                'color' => 'bg-emerald-600 hover:bg-emerald-700',
                'dot_color' => 'bg-emerald-600',
                'hex' => '#059669',
            ],
            [
                'label' => 'Risiko Tinggi (< 20 / > 35 th)',
                'legend' => 'Risiko Tinggi (< 20 / > 35 th)',
                'count' => $usiaRisiko,
                'color' => 'bg-red-500 hover:bg-red-600',
                'dot_color' => 'bg-red-500',
                'hex' => '#ef4444',
            ],
        ];

        // Determine current chart data
        $chartMap = [
            'anemia' => [
                'title' => 'Ibu Hamil Berdasarkan Anemia',
                'subtitle' => 'Distribusi Status Anemia',
                'data' => $anemiaData,
            ],
            'lila' => [
                'title' => 'Ibu Hamil Berdasarkan LILA',
                'subtitle' => 'Distribusi Lingkar Lengan Atas (KEK)',
                'data' => $lilaData,
            ],
            'imt' => [
                'title' => 'Ibu Hamil Berdasarkan IMT',
                'subtitle' => 'Distribusi Indeks Massa Tubuh',
                'data' => $imtData,
            ],
            'usia' => [
                'title' => 'Ibu Hamil Berdasarkan Usia Ibu',
                'subtitle' => 'Distribusi Usia Reproduksi & Risiko',
                'data' => $usiaData,
            ],
        ];

        $currentChart = $chartMap[$this->activeChart] ?? $chartMap['anemia'];
        $currentChartData = $currentChart['data'];
        $maxChartCount = max(array_column($currentChartData, 'count')) ?: 1;

        return view('livewire.dashboard.index', [
            'greeting' => $greeting,
            'totalIbuHamil' => $totalIbuHamil,
            'countTinggi' => $countPriorityTinggi,
            'countSedang' => $countPrioritySedang,
            'countRendah' => $countPriorityRendah,
            'topPriorities' => $topPriorities,
            'activeChart' => $this->activeChart,
            'chartInfo' => $currentChart,
            'currentChartData' => $currentChartData,
            'maxChartCount' => $maxChartCount,
        ])->layout('layouts.app', [
            'title' => 'Dashboard',
            'breadcrumb' => 'Dashboard',
        ]);
    }
}
