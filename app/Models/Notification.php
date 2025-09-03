<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'audience',
        'title',
        'message',
        'type',
        'priority',
        'action_url',
        'is_read',
        'read_at',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'metadata' => 'array',
        'is_read'  => 'boolean',
        'read_at'  => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * Scope notifications that belong to a user or to all.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('audience', 'all')
              ->orWhere(function ($q2) use ($userId) {
                  $q2->where('audience', 'user')
                     ->where('user_id', $userId);
              });
        });
    }
}

