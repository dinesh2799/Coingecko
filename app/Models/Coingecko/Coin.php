<?php

namespace App\Models\Coingecko;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coin extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'symbol',
        'name',
    ];
    public function platforms() {
        return $this->hasMany(Platform::class);
    }
}
