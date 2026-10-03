<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TopsisResult extends Model
{
    use HasFactory;

    protected $table = 'topsis_results';

    protected $fillable = [
        'ibu_hamil_id',
        'ahp_calculation_id',
        'score_anemia',
        'score_imt',
        'score_lila',
        'score_usia',
        'd_plus',
        'd_minus',
        'preference_score',
        'rank',
        'priority',
        'main_risk_factors',
        'recommendation',
    ];

    protected function casts(): array
    {
        return [
            'score_anemia' => 'float',
            'score_imt' => 'float',
            'score_lila' => 'float',
            'score_usia' => 'float',
            'd_plus' => 'float',
            'd_minus' => 'float',
            'preference_score' => 'float',
            'rank' => 'integer',
        ];
    }

    public function ibuHamil(): BelongsTo
    {
        return $this->belongsTo(IbuHamil::class, 'ibu_hamil_id');
    }

    public function ahpCalculation(): BelongsTo
    {
        return $this->belongsTo(AhpCalculation::class, 'ahp_calculation_id');
    }

    public function getPriorityBadgeColorAttribute(): string
    {
        return match ($this->priority) {
            'Tinggi' => 'bg-red-100 text-red-700 border-red-200',
            'Sedang' => 'bg-amber-100 text-amber-700 border-amber-200',
            'Rendah' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
            default => 'bg-gray-100 text-gray-700 border-gray-200',
        };
    }
}
