<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AhpResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'lambda_max',
        'ci',
        'cr',
        'ri',
        'is_consistent',
        'weights',
        'normalized_matrix',
        'priority_vector',
        'pairwise_matrix_snapshot',
    ];

    protected $casts = [
        'lambda_max' => 'float',
        'ci' => 'float',
        'cr' => 'float',
        'ri' => 'float',
        'is_consistent' => 'boolean',
        'weights' => 'array',
        'normalized_matrix' => 'array',
        'priority_vector' => 'array',
        'pairwise_matrix_snapshot' => 'array',
    ];

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class);
    }
}
