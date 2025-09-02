<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnershipMessage extends Model
{

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'contact_number',
        'message',
    ];
}
