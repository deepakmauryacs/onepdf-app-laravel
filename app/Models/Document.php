<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','filename','filepath','size','share_token','share_expires_at',
    ];

    protected $casts = [
        'share_expires_at' => 'datetime',
    ];

    public function getViewerUrlAttribute(): ?string
    {
        return $this->link? route('public.viewer', ['doc' => $this->link->slug]) : null;
    }

    public function link()
    {
        return $this->hasOne(\App\Models\Link::class, 'document_id');
    }



}
