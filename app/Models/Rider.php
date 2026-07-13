<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Rider extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'outlet_id',
        'name',
        'username',
        'password',
        'phone',
        'account_status',
        'operational_status',
        'note',
    ];

    protected $hidden = [
        'password',
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}
