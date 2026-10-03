<?php

namespace App\Livewire\Setting;

use App\Models\Setting;
use Livewire\Component;

class Index extends Component
{
    public string $namaPuskesmas = '';
    public float $thresholdHigh = 0.80;
    public float $thresholdMedium = 0.60;
    public string $desaListText = '';

    public function mount()
    {
        $this->namaPuskesmas = Setting::get('nama_puskesmas', 'Puskesmas Kecamatan Taman Sari');
        $this->thresholdHigh = (float) Setting::get('threshold_high', '0.80');
        $this->thresholdMedium = (float) Setting::get('threshold_medium', '0.60');

        $desaArray = json_decode(Setting::get('desa_list', '[]'), true) ?: [
            'Taman Sari', 'Maphar', 'Tangki', 'Mangga Besar', 'Keagungan', 'Glodok', 'Pinangsia', 'Krukut'
        ];
        $this->desaListText = implode(', ', $desaArray);
    }

    public function save()
    {
        $this->validate([
            'namaPuskesmas' => 'required|string|max:255',
            'thresholdHigh' => 'required|numeric|min:0|max:1',
            'thresholdMedium' => 'required|numeric|min:0|max:1',
        ], [
            'namaPuskesmas.required' => 'Nama puskesmas wajib diisi.',
            'thresholdHigh.required' => 'Ambang batas prioritas tinggi wajib diisi.',
            'thresholdMedium.required' => 'Ambang batas prioritas sedang wajib diisi.',
        ]);

        Setting::set('nama_puskesmas', $this->namaPuskesmas);
        Setting::set('threshold_high', (string) $this->thresholdHigh);
        Setting::set('threshold_medium', (string) $this->thresholdMedium);

        // Process desa list
        $desaArray = array_values(array_filter(array_map('trim', explode(',', $this->desaListText))));
        Setting::set('desa_list', json_encode($desaArray));

        session()->flash('success', 'Pengaturan sistem berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.setting.index')->layout('layouts.app', [
            'title' => 'Pengaturan Sistem',
            'breadcrumb' => 'Pengaturan',
        ]);
    }
}
