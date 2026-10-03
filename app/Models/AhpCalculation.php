<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AhpCalculation extends Model
{
    use HasFactory;

    protected $table = 'ahp_calculations';

    protected $fillable = [
        'lambda_max',
        'ci',
        'cr',
        'is_consistent',
        'is_active',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'lambda_max' => 'float',
            'ci' => 'float',
            'cr' => 'float',
            'is_consistent' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function comparisons(): HasMany
    {
        return $this->hasMany(AhpComparison::class, 'ahp_calculation_id');
    }

    public function weights(): HasMany
    {
        return $this->hasMany(AhpWeight::class, 'ahp_calculation_id');
    }

    public function topsisResults(): HasMany
    {
        return $this->hasMany(TopsisResult::class, 'ahp_calculation_id');
    }
}
