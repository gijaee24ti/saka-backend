<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'outlet_id',
        'menu_id',
        'rider_id',
        'stock_status',
        'note',
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function rider()
    {
        return $this->belongsTo(Rider::class);
    }

    protected function casts(): array
    {
        return [
            'updated_at' => 'datetime',
        ];
    }
}
