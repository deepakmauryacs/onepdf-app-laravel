<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Document;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'lead_form_id',
        'name',
        'email',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function leadForm()
    {
        return $this->belongsTo(LeadForm::class);
    }
}
