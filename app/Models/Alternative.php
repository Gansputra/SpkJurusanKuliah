<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alternative extends Model
{
    use HasFactory;

    protected $fillable = ['kode', 'nama', 'deskripsi', 'active'];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function scores()
    {
        return $this->hasMany(AlternativeScore::class);
    }

    public function recommendationDetails()
    {
        return $this->hasMany(RecommendationDetail::class);
    }

    public function getScoreForCriteria(int $criteriaId): float
    {
        $score = $this->scores()->where('criteria_id', $criteriaId)->first();
        return $score ? (float) $score->nilai : 0;
    }
}
