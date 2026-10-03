<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AhpWeight extends Model
{
    use HasFactory;

    protected $table = 'ahp_weights';

    protected $fillable = [
        'ahp_calculation_id',
        'criterion_id',
        'eigen_vector',
        'weight_percentage',
    ];

    protected function casts(): array
    {
        return [
            'eigen_vector' => 'float',
            'weight_percentage' => 'float',
        ];
    }

    public function calculation(): BelongsTo
    {
        return $this->belongsTo(AhpCalculation::class, 'ahp_calculation_id');
    }

    public function criterion(): BelongsTo
    {
        return $this->belongsTo(Criterion::class, 'criterion_id');
    }
}
