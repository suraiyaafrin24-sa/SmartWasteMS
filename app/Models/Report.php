<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['user_id', 'complained_person', 'incident_date', 'subject', 'description', 'status'];

    protected $casts = [
        'incident_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
