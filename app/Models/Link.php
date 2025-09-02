<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    // Table: links (default)
    // We only have created_at (no updated_at)
    public $timestamps = false;

    protected $fillable = [
        'document_id',
        'user_id',
        'slug',
        'permissions',
        'created_at',
    ];

    protected $casts = [
        'permissions' => 'array',
        'created_at'  => 'datetime',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
