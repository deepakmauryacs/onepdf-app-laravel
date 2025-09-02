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

    public function getPublicUrlAttribute(): ?string
    {
        return $this->share_token ? route('vendor.files.public', $this->share_token) : null;
    }
}
