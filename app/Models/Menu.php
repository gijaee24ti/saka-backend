<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'name',
        'category',
        'cup_price',
        'price_500',
        'price_1l',
        'description',
        'durability',
        'image',
        'status',
    ];

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    protected function casts(): array
    {
        return [
            'cup_price' => 'integer',
            'price_500' => 'integer',
            'price_1l' => 'integer',
        ];
    }
}
