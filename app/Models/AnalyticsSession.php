<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsSession extends Model
{
    protected $fillable = [
        'session_id',
        'user_id',
        'ip',
        'city',
        'country',
        'device',
        'platform',
        'browser',
    ];

    public function events() {
        return $this->hasMany(AnalyticsEvent::class, 'session_id');
    }
}
