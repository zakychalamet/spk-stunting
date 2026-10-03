<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AhpComparison extends Model
{
    use HasFactory;

    protected $table = 'ahp_comparisons';

    protected $fillable = [
        'ahp_calculation_id',
        'criterion1_id',
        'criterion2_id',
        'value',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'float',
        ];
    }

    public function calculation(): BelongsTo
    {
        return $this->belongsTo(AhpCalculation::class, 'ahp_calculation_id');
    }

    public function criterion1(): BelongsTo
    {
        return $this->belongsTo(Criterion::class, 'criterion1_id');
    }

    public function criterion2(): BelongsTo
    {
        return $this->belongsTo(Criterion::class, 'criterion2_id');
    }
}
