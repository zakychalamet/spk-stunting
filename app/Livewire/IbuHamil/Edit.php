<?php

namespace App\Livewire\IbuHamil;

use App\Models\IbuHamil;
use App\Models\Setting;
use Carbon\Carbon;
use Livewire\Component;

class Edit extends Component
{
    public IbuHamil $ibuHamil;

    // Informasi Pribadi
    public string $nama = '';
    public string $tanggal_lahir = '';
    public int $usia = 0;
    public string $nomor_telepon = '';
    public string $desa_kelurahan = '';

    // Informasi Kehamilan
    public string $hpht = '';
    public string $hpl = '';
    public int $usia_kehamilan_minggu = 0;
    public string $status_kehamilan = 'Trimester I';

    // Informasi Kesehatan (Hanya 4 field: Status Anemia, IMT, LILA, Berat Badan Sebelum Hamil)
    public string $status_anemia = 'Tidak Anemia';
    public ?float $imt = null;
    public ?float $lila = null;
    public ?float $berat_badan_sebelum_hamil = null;

    protected $rules = [
        'nama' => 'required|string|max:255',
        'tanggal_lahir' => 'required|date',
        'hpht' => 'required|date',
        'hpl' => 'required|date',
        'usia_kehamilan_minggu' => 'required|integer|min:0|max:45',
        'status_kehamilan' => 'required|string',
        'status_anemia' => 'required|string',
        'imt' => 'required|numeric|min:0',
        'lila' => 'required|numeric|min:0',
        'nomor_telepon' => 'nullable|string|max:30',
        'desa_kelurahan' => 'nullable|string|max:100',
        'berat_badan_sebelum_hamil' => 'nullable|numeric|min:0',
    ];

    protected $messages = [
        'nama.required' => 'Nama lengkap wajib diisi.',
        'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
        'tanggal_lahir.date' => 'Format tanggal lahir tidak valid.',
        'hpht.required' => 'Hari Pertama Haid Terakhir (HPHT) wajib diisi.',
        'hpl.required' => 'Hari Perkiraan Lahir (HPL) wajib diisi.',
        'status_anemia.required' => 'Status anemia wajib dipilih.',
        'imt.required' => 'Indeks Massa Tubuh (IMT) wajib diisi.',
        'lila.required' => 'Lingkar Lengan Atas (LILA) wajib diisi.',
    ];

    public function mount(IbuHamil $ibuHamil)
    {
        $this->ibuHamil = $ibuHamil;
        $this->nama = $ibuHamil->nama;
        $this->tanggal_lahir = $ibuHamil->tanggal_lahir ? $ibuHamil->tanggal_lahir->format('Y-m-d') : '';
        $this->usia = $ibuHamil->usia;
        $this->nomor_telepon = $ibuHamil->nomor_telepon ?? '';
        $this->desa_kelurahan = $ibuHamil->desa_kelurahan ?? '';

        $this->hpht = $ibuHamil->hpht ? $ibuHamil->hpht->format('Y-m-d') : '';
        $this->hpl = $ibuHamil->hpl ? $ibuHamil->hpl->format('Y-m-d') : '';
        $this->usia_kehamilan_minggu = (int) $ibuHamil->usia_kehamilan_minggu;
        $this->status_kehamilan = $ibuHamil->status_kehamilan ?? 'Trimester I';

        $this->status_anemia = $ibuHamil->status_anemia_label;
        $this->imt = (float) $ibuHamil->imt;
        $this->lila = (float) $ibuHamil->lila;
        $this->berat_badan_sebelum_hamil = $ibuHamil->berat_badan_sebelum_hamil;
    }

    public function updatedTanggalLahir($value)
    {
        if (!empty($value)) {
            try {
                $this->usia = Carbon::parse($value)->age;
            } catch (\Exception $e) {
                $this->usia = 0;
            }
        }
    }

    public function updatedHpht($value)
    {
        if (!empty($value)) {
            try {
                $carbonHpht = Carbon::parse($value);
                $this->hpl = $carbonHpht->copy()->addDays(280)->format('Y-m-d');
                $weeks = (int) $carbonHpht->diffInWeeks(Carbon::now());
                $this->usia_kehamilan_minggu = max(0, min(42, $weeks));

                if ($this->usia_kehamilan_minggu <= 12) {
                    $this->status_kehamilan = 'Trimester I';
                } elseif ($this->usia_kehamilan_minggu <= 27) {
                    $this->status_kehamilan = 'Trimester II';
                } else {
                    $this->status_kehamilan = 'Trimester III';
                }
            } catch (\Exception $e) {
            }
        }
    }

    public function save()
    {
        $this->validate();

        $this->ibuHamil->update([
            'nama' => $this->nama,
            'tanggal_lahir' => $this->tanggal_lahir,
            'nomor_telepon' => $this->nomor_telepon,
            'desa_kelurahan' => $this->desa_kelurahan,
            'hpht' => $this->hpht,
            'hpl' => $this->hpl,
            'usia_kehamilan_minggu' => $this->usia_kehamilan_minggu,
            'status_kehamilan' => $this->status_kehamilan,
            'status_anemia' => $this->status_anemia,
            'imt' => $this->imt,
            'lila' => $this->lila,
            'berat_badan_sebelum_hamil' => $this->berat_badan_sebelum_hamil,
        ]);

        session()->flash('success', "Data ibu hamil {$this->ibuHamil->nama} berhasil diperbarui.");
        return redirect()->route('ibu-hamil.index');
    }

    public function render()
    {
        $desaList = json_decode(Setting::get('desa_list', '[]'), true) ?: [
            'Taman Sari', 'Maphar', 'Tangki', 'Mangga Besar', 'Keagungan', 'Glodok', 'Pinangsia', 'Krukut'
        ];

        return view('livewire.ibu-hamil.edit', [
            'desaList' => $desaList,
        ])->layout('layouts.app', [
            'title' => 'Edit Ibu Hamil',
            'breadcrumb' => 'Edit Ibu Hamil',
        ]);
    }
}
