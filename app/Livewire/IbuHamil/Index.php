<?php

namespace App\Livewire\IbuHamil;

use App\Models\IbuHamil;
use App\Models\Setting;
use App\Models\TopsisResult;
use App\Services\ExcelService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public string $search = '';
    public string $filterAnemia = '';
    public string $filterDesa = '';
    public string $filterKehamilan = '';
    public bool $showFilter = false;

    // Delete Modal
    public ?int $deleteId = null;
    public string $deleteName = '';
    public bool $showDeleteModal = false;

    // Import Modal
    public bool $showImportModal = false;
    public $importFile = null;
    public ?string $importMessage = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterAnemia' => ['except' => ''],
        'filterDesa' => ['except' => ''],
        'filterKehamilan' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterAnemia()
    {
        $this->resetPage();
    }

    public function updatingFilterDesa()
    {
        $this->resetPage();
    }

    public function updatingFilterKehamilan()
    {
        $this->resetPage();
    }

    public function toggleFilter()
    {
        $this->showFilter = !$this->showFilter;
    }

    public function resetFilters()
    {
        $this->reset(['search', 'filterAnemia', 'filterDesa', 'filterKehamilan']);
        $this->resetPage();
    }

    public function confirmDelete(int $id, string $name)
    {
        if (!auth()->user()->canManageIbuHamil()) {
            abort(403, 'Anda tidak memiliki hak akses untuk menghapus data ibu hamil.');
        }

        $this->deleteId = $id;
        $this->deleteName = $name;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if (!auth()->user()->canManageIbuHamil()) {
            abort(403, 'Anda tidak memiliki hak akses untuk menghapus data ibu hamil.');
        }

        if ($this->deleteId) {
            $ibuHamil = IbuHamil::find($this->deleteId);
            if ($ibuHamil) {
                TopsisResult::where('ibu_hamil_id', $this->deleteId)->delete();
                $ibuHamil->delete();
                session()->flash('success', "Data {$this->deleteName} berhasil dihapus.");
            }
        }

        $this->showDeleteModal = false;
        $this->reset(['deleteId', 'deleteName']);
    }

    public function importExcel()
    {
        if (!auth()->user()->canManageIbuHamil()) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengimpor data ibu hamil.');
        }

        $this->importMessage = null;

        $this->validate([
            'importFile' => [
                'required',
                'file',
                'max:5120',
                function ($attribute, $value, $fail) {
                    $ext = strtolower($value->getClientOriginalExtension());
                    if (!in_array($ext, ['csv', 'txt', 'xlsx', 'xls'])) {
                        $fail('Format file harus berupa CSV (.csv) atau Excel (.xlsx/.xls).');
                    }
                },
            ],
        ], [
            'importFile.required' => 'Silakan pilih file CSV/Excel terlebih dahulu.',
        ]);

        try {
            $excelService = new ExcelService();
            $res = $excelService->importIbuHamil($this->importFile->getRealPath());

            if ($res['success']) {
                $count = $res['count'];
                if ($count > 0) {
                    $this->showImportModal = false;
                    $this->reset('importFile');
                    $extra = !empty($res['errors']) ? ' (' . count($res['errors']) . ' baris dilewati karena format tidak sesuai)' : '';
                    session()->flash('success', "Berhasil mengimpor $count data ibu hamil.$extra");
                } else {
                    $errMsg = !empty($res['errors']) ? implode(' ', array_slice($res['errors'], 0, 3)) : 'Tidak ada baris data yang berhasil diimpor.';
                    $this->importMessage = mb_convert_encoding($errMsg, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252');
                }
            } else {
                $errMsg = $res['message'] ?? 'Format file tidak sesuai template.';
                $this->importMessage = mb_convert_encoding($errMsg, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252');
            }
        } catch (\Throwable $e) {
            $this->importMessage = 'File tidak dapat diproses karena format atau karakter tidak sesuai template.';
        }
    }

    public function render()
    {
        $query = IbuHamil::query();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                  ->orWhere('kode_ibu_hamil', 'like', '%' . $this->search . '%')
                  ->orWhere('desa_kelurahan', 'like', '%' . $this->search . '%')
                  ->orWhere('nomor_telepon', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->filterAnemia)) {
            $filter = strtolower($this->filterAnemia);
            if ($filter === 'normal') {
                $query->where(function($q) {
                    $q->where('status_anemia', 'like', '%normal%')
                      ->orWhere('status_anemia', 'like', '%tidak%')
                      ->orWhere('status_anemia', 'like', '%11%');
                });
            } else {
                $query->where('status_anemia', 'like', '%' . $this->filterAnemia . '%');
            }
        }

        if (!empty($this->filterDesa)) {
            $query->where('desa_kelurahan', $this->filterDesa);
        }

        if (!empty($this->filterKehamilan)) {
            $query->where('status_kehamilan', $this->filterKehamilan);
        }

        $items = $query->latest()->paginate(10);

        $desaList = json_decode(Setting::get('desa_list', '[]'), true) ?: [
            'Parakancanggah', 'Cendana', 'Krandegan', 'Semarang', 'Sokayasa', 'Sokanandi'
        ];

        return view('livewire.ibu-hamil.index', [
            'items' => $items,
            'desaList' => $desaList,
        ])->layout('layouts.app', [
            'title' => 'Daftar Ibu Hamil',
            'breadcrumb' => 'Daftar Ibu Hamil',
        ]);
    }
}
