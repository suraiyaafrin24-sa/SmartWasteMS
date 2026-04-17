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

    public function collector()
    {
        return $this->belongsTo(User::class, 'collector_id');
    }
}
