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

    // Informasi Kesehatan
    public string $status_anemia = 'Tidak Anemia';
    public ?float $kadar_hb = null;
    public float $imt = 0;
    public float $lila = 0;
    public ?float $berat_badan_sebelum_hamil = null;
    public ?float $tinggi_badan = null;

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
        'tinggi_badan' => 'nullable|numeric|min:0',
        'kadar_hb' => 'nullable|numeric|min:0',
    ];

    protected $messages = [
        'nama.required' => 'Nama lengkap wajib diisi.',
        'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
        'tanggal_lahir.date' => 'Format tanggal lahir tidak valid.',
        'hpht.required' => 'Hari Pertama Haid Terakhir (HPHT) wajib diisi.',
        'hpl.required' => 'Hari Perkiraan Lahir (HPL) wajib diisi.',
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

        $this->status_anemia = $ibuHamil->status_anemia ?? 'Tidak Anemia';
        $this->kadar_hb = $ibuHamil->kadar_hb;
        $this->imt = (float) $ibuHamil->imt;
        $this->lila = (float) $ibuHamil->lila;
        $this->berat_badan_sebelum_hamil = $ibuHamil->berat_badan_sebelum_hamil;
        $this->tinggi_badan = $ibuHamil->tinggi_badan;
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

    public function updatedBeratBadanSebelumHamil()
    {
        $this->recalculateImt();
    }

    public function updatedTinggiBadan()
    {
        $this->recalculateImt();
    }

    protected function recalculateImt()
    {
        if ($this->berat_badan_sebelum_hamil && $this->tinggi_badan && $this->tinggi_badan > 0) {
            $tbMeter = $this->tinggi_badan / 100;
            $this->imt = round($this->berat_badan_sebelum_hamil / ($tbMeter * $tbMeter), 2);
        }
    }

    public function updatedKadarHb($value)
    {
        if ($value !== null && $value !== '') {
            $hb = (float) $value;
            if ($hb >= 11.0) {
                $this->status_anemia = 'Tidak Anemia';
            } elseif ($hb >= 10.0) {
                $this->status_anemia = 'Anemia Ringan';
            } elseif ($hb >= 7.0) {
                $this->status_anemia = 'Anemia Sedang';
            } else {
                $this->status_anemia = 'Anemia Berat';
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
            'kadar_hb' => $this->kadar_hb,
            'imt' => $this->imt,
            'lila' => $this->lila,
            'berat_badan_sebelum_hamil' => $this->berat_badan_sebelum_hamil,
            'tinggi_badan' => $this->tinggi_badan,
        ]);

        session()->flash('success', 'Perubahan data ibu hamil berhasil disimpan.');
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
            'title' => 'Edit Data Ibu Hamil',
            'breadcrumb' => 'Edit Data Ibu Hamil',
        ]);
    }
}
