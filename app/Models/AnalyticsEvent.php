<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsEvent extends Model
{
    protected $fillable = ['session_id', 'event_type', 'target', 'page_number', 'duration'];

    public function session() {
        return $this->belongsTo(AnalyticsSession::class, 'session_id');
    }
}
