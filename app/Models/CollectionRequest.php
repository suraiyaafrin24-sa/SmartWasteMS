<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollectionRequest extends Model
{
    protected $fillable = [
        'user_id',
        'collector_id',
        'type',
        'scheduled_date',
        'scheduled_time',
        'status',
        'weight',
        'details',
        'address',
        'phone',
        'zone',
        'sector',
        'road',
        'shift',
        'payment_status',
        'transaction_id'
    ];

    protected $casts = [
        'scheduled_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function collector()
    {
        return $this->belongsTo(User::class, 'collector_id');
    }
}
