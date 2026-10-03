<?php

namespace App\Livewire\IbuHamil;

use App\Models\IbuHamil;
use App\Models\Setting;
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
        $this->deleteId = $id;
        $this->deleteName = $name;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->deleteId) {
            $ibuHamil = IbuHamil::find($this->deleteId);
            if ($ibuHamil) {
                $ibuHamil->delete();
                session()->flash('success', "Data {$this->deleteName} berhasil dihapus.");
            }
        }

        $this->showDeleteModal = false;
        $this->reset(['deleteId', 'deleteName']);
    }

    public function importExcel()
    {
        $this->validate([
            'importFile' => 'required|file|mimes:csv,txt,xlsx,xls|max:5120',
        ], [
            'importFile.required' => 'Silakan pilih file CSV/Excel terlebih dahulu.',
            'importFile.mimes' => 'Format file harus berupa CSV (.csv) atau Excel (.xlsx/.xls).',
        ]);

        $excelService = new ExcelService();
        $res = $excelService->importIbuHamil($this->importFile->getRealPath());

        if ($res['success']) {
            $count = $res['count'];
            $this->showImportModal = false;
            $this->reset('importFile');
            session()->flash('success', "Berhasil mengimpor $count data ibu hamil.");
        } else {
            $this->importMessage = $res['message'] ?? 'Gagal mengimpor file.';
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
            $query->where('status_anemia', $this->filterAnemia);
        }

        if (!empty($this->filterDesa)) {
            $query->where('desa_kelurahan', $this->filterDesa);
        }

        if (!empty($this->filterKehamilan)) {
            $query->where('status_kehamilan', $this->filterKehamilan);
        }

        $items = $query->latest()->paginate(10);

        $desaList = json_decode(Setting::get('desa_list', '[]'), true) ?: [
            'Taman Sari', 'Maphar', 'Tangki', 'Mangga Besar', 'Keagungan', 'Glodok', 'Pinangsia', 'Krukut'
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
