<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LinkAnalytics extends Model
{
    public $timestamps = false; // only created_at is present

    protected $fillable = [
        'link_id',
        'event',
        'meta',
        'ip',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'meta'       => 'array',
        'created_at' => 'datetime',
    ];

    public function link()
    {
        return $this->belongsTo(Link::class);
    }
}
