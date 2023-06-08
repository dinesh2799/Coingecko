<?php

namespace App\Models\Coingecko;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'value',
        'coin_id',
    ];
    public function coin()
    {
        return $this->belongsTo(Coin::class);
    }
}
