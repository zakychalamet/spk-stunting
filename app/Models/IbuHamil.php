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
}
