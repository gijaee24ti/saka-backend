<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedback';

    protected $fillable = [
        'outlet_id',
        'customer_name',
        'phone',
        'branch',
        'type',
        'category',
        'rating',
        'message',
        'status',
        'feedback_date',
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'feedback_date' => 'date:Y-m-d',
        ];
    }
}
