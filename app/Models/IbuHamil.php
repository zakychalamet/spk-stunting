<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class IbuHamil extends Model
{
    use HasFactory;

    protected $table = 'ibu_hamil';

    protected $fillable = [
        'kode_ibu_hamil',
        'nama',
        'tanggal_lahir',
        'nomor_telepon',
        'desa_kelurahan',
        'hpht',
        'hpl',
        'usia_kehamilan_minggu',
        'status_kehamilan',
        'status_anemia',
        'kadar_hb',
        'imt',
        'lila',
        'berat_badan_sebelum_hamil',
        'tinggi_badan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'hpht' => 'date',
            'hpl' => 'date',
            'usia_kehamilan_minggu' => 'integer',
            'kadar_hb' => 'float',
            'imt' => 'float',
            'lila' => 'float',
            'berat_badan_sebelum_hamil' => 'float',
            'tinggi_badan' => 'float',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($ibuHamil) {
            if (empty($ibuHamil->kode_ibu_hamil)) {
                $year = date('Y');
                $count = static::whereYear('created_at', $year)->count() + 1;
                $ibuHamil->kode_ibu_hamil = sprintf('P-%s-%03d', $year, $count);
            }
        });
    }

    public function topsisResult(): HasOne
    {
        return $this->hasOne(TopsisResult::class, 'ibu_hamil_id');
    }

    public function getUsiaAttribute(): int
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->age : 0;
    }

    public function getUsiaLabelAttribute(): string
    {
        return $this->usia . ' tahun';
    }

    public function getUsiaKehamilanLabelAttribute(): string
    {
        return ($this->usia_kehamilan_minggu ?? 0) . ' minggu';
    }

    public function getStatusAnemiaLabelAttribute(): string
    {
        $status = strtolower(trim((string) $this->status_anemia));
        if (str_contains($status, 'berat') || str_contains($status, '< 7') || str_contains($status, '<7')) {
            return 'Berat (< 7)';
        }
        if (str_contains($status, 'sedang') || str_contains($status, '7 - 9') || str_contains($status, '7-9') || str_contains($status, '7–9')) {
            return 'Sedang (7 - 9,9)';
        }
        if (str_contains($status, 'ringan') || str_contains($status, '10 - 10') || str_contains($status, '10-10') || str_contains($status, '10–10')) {
            return 'Ringan (10 - 10,9)';
        }
        if (str_contains($status, 'normal') || str_contains($status, 'tidak') || str_contains($status, '11')) {
            return 'Normal (≥ 11)';
        }

        // Fallback to kadar_hb if status_anemia is empty
        $hb = (float) $this->kadar_hb;
        if ($hb > 0) {
            if ($hb >= 11.0) return 'Normal (≥ 11)';
            if ($hb >= 10.0) return 'Ringan (10 - 10,9)';
            if ($hb >= 7.0) return 'Sedang (7 - 9,9)';
            return 'Berat (< 7)';
        }

        return $this->status_anemia ?: 'Normal (≥ 11)';
    }
}
