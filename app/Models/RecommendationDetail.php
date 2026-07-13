<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecommendationDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'recommendation_id',
        'alternative_id',
        'nilai_preferensi',
        'd_plus',
        'd_minus',
        'ranking',
    ];

    protected $casts = [
        'nilai_preferensi' => 'float',
        'd_plus' => 'float',
        'd_minus' => 'float',
        'ranking' => 'integer',
    ];

    public function recommendation()
    {
        return $this->belongsTo(Recommendation::class);
    }

    public function alternative()
    {
        return $this->belongsTo(Alternative::class);
    }
}
