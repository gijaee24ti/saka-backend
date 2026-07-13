<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    protected $fillable = [
        'branch',
        'vehicle',
        'open_time',
        'close_time',
        'status',
        'address',
        'maps_link',
        'note',
    ];

    public function riders()
    {
        return $this->hasMany(Rider::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class);
    }
}
