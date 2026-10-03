<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Criterion extends Model
{
    use HasFactory;

    protected $table = 'criteria';

    protected $fillable = [
        'code',
        'name',
        'type',
        'description',
    ];

    public function scales(): HasMany
    {
        return $this->hasMany(CriterionScale::class, 'criterion_id')->orderBy('score', 'asc');
    }

    public function ahpWeights(): HasMany
    {
        return $this->hasMany(AhpWeight::class, 'criterion_id');
    }

    public function comparisonsAsFirst(): HasMany
    {
        return $this->hasMany(AhpComparison::class, 'criterion1_id');
    }

    public function comparisonsAsSecond(): HasMany
    {
        return $this->hasMany(AhpComparison::class, 'criterion2_id');
    }
}
