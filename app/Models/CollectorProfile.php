<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'assigned_vehicle',
        'license_number',
        'shift_preference',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
