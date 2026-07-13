<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    use HasFactory;

    protected $table = 'criteria';

    protected $fillable = ['kode', 'nama', 'tipe', 'bobot', 'urutan'];

    protected $casts = [
        'bobot' => 'float',
        'urutan' => 'integer',
    ];

    public function alternativeScores()
    {
        return $this->hasMany(AlternativeScore::class);
    }

    public function pairwiseAsFirst()
    {
        return $this->hasMany(PairwiseMatrix::class, 'criteria1_id');
    }

    public function pairwiseAsSecond()
    {
        return $this->hasMany(PairwiseMatrix::class, 'criteria2_id');
    }

    public function isBenefit(): bool
    {
        return $this->tipe === 'benefit';
    }

    public function isCost(): bool
    {
        return $this->tipe === 'cost';
    }
}
