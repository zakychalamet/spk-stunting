<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CriterionScale extends Model
{
    use HasFactory;

    protected $table = 'criterion_scales';

    protected $fillable = [
        'criterion_id',
        'parameter',
        'score',
        'label',
        'category',
        'min_value',
        'max_value',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'integer',
            'min_value' => 'float',
            'max_value' => 'float',
        ];
    }

    public function criterion(): BelongsTo
    {
        return $this->belongsTo(Criterion::class, 'criterion_id');
    }
}
