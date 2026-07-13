<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PairwiseMatrix extends Model
{
    use HasFactory;

    protected $table = 'pairwise_matrix';

    protected $fillable = ['criteria1_id', 'criteria2_id', 'nilai'];

    protected $casts = [
        'nilai' => 'float',
    ];

    public function criteria1()
    {
        return $this->belongsTo(Criteria::class, 'criteria1_id');
    }

    public function criteria2()
    {
        return $this->belongsTo(Criteria::class, 'criteria2_id');
    }
}
