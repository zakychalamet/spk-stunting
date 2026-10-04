<?php

use App\Livewire\Ahp\Comparison as AhpComparison;
use App\Livewire\Ahp\Detail as AhpDetail;
use App\Livewire\Auth\Login;
use App\Livewire\Criteria\Index as CriteriaIndex;
use App\Livewire\Dashboard\Index as DashboardIndex;
use App\Livewire\IbuHamil\Create as IbuHamilCreate;
use App\Livewire\IbuHamil\Edit as IbuHamilEdit;
use App\Livewire\IbuHamil\Index as IbuHamilIndex;
use App\Livewire\IbuHamil\Show as IbuHamilShow;
use App\Livewire\Ranking\Show as RankingShow;
use App\Livewire\Setting\Index as SettingIndex;
use App\Livewire\Topsis\Calculation as TopsisCalculation;
use App\Livewire\Topsis\Detail as TopsisDetail;
use App\Livewire\User\Index as UserIndex;
use App\Services\ExcelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Authentication
Route::get('/login', Login::class)->name('login');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/', DashboardIndex::class)->name('home');
    Route::get('/dashboard', DashboardIndex::class)->name('dashboard');

    // Ibu Hamil Management
    Route::get('/ibu-hamil', IbuHamilIndex::class)->name('ibu-hamil.index');
    Route::get('/ibu-hamil/create', IbuHamilCreate::class)->name('ibu-hamil.create');
    Route::get('/ibu-hamil/{ibuHamil}/edit', IbuHamilEdit::class)->name('ibu-hamil.edit');
    Route::get('/ibu-hamil/{ibuHamil}', IbuHamilShow::class)->name('ibu-hamil.show');

    // Excel Export & Template
    Route::get('/ibu-hamil-export', function (ExcelService $excelService) {
        return $excelService->exportIbuHamil();
    })->name('ibu-hamil.export');

    Route::get('/ibu-hamil-template', function (ExcelService $excelService) {
        return $excelService->exportTemplate();
    })->name('ibu-hamil.template');

    // Kriteria & Skala Penilaian
    Route::get('/kriteria', CriteriaIndex::class)->name('kriteria.index');

    // AHP Weight Calculation
    Route::get('/perhitungan-bobot', AhpComparison::class)->name('ahp.index');
    Route::get('/perhitungan-bobot/detail', AhpDetail::class)->name('ahp.detail');

    // TOPSIS Calculation
    Route::get('/perhitungan-topsis', TopsisCalculation::class)->name('topsis.index');
    Route::get('/perhitungan-topsis/detail', TopsisDetail::class)->name('topsis.detail');

    // Hasil Perangkingan (Dialihkan ke Perhitungan TOPSIS)
    Route::redirect('/hasil-perangkingan', '/perhitungan-topsis');
    Route::get('/hasil-perangkingan/{id}', RankingShow::class)->name('ranking.show');

    // Pengaturan
    Route::get('/pengaturan', SettingIndex::class)->name('setting.index');

    // Manajemen Pengguna (Admin Only)
    Route::middleware(['role:admin,administrator'])->group(function () {
        Route::get('/pengguna', UserIndex::class)->name('user.index');
    });
});
