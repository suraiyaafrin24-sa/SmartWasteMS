<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DutyRoster extends Model
{
    use HasFactory;

    protected $fillable = [
        'collector_id',
        'sector',
        'road',
        'date',
        'shift',
        'status'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function collector()
    {
        return $this->belongsTo(User::class, 'collector_id');
    }

    public function isActive()
    {
        return $this->date == now()->format('Y-m-d');
    }

    public function isPast()
    {
        return $this->date < now()->format('Y-m-d');
    }
}
