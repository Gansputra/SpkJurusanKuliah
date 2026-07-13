<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_name',
        'ahp_result_id',
        'topsis_steps',
        'calculated_at',
    ];

    protected $casts = [
        'topsis_steps' => 'array',
        'calculated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ahpResult()
    {
        return $this->belongsTo(AhpResult::class);
    }

    public function details()
    {
        return $this->hasMany(RecommendationDetail::class)->orderBy('ranking');
    }

    public function topResult()
    {
        return $this->hasOne(RecommendationDetail::class)->orderBy('ranking');
    }
}
