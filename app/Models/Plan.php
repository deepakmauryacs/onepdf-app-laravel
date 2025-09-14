<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name', 'inr_price', 'usd_price', 'billing_cycle', 'storage', 'monthly_file'
    ];

    protected $casts = [
        'inr_price' => 'decimal:2',
        'usd_price' => 'decimal:2',
    ];

    /**
     * Convert the storage string (e.g. "20 MB") to bytes.
     */
    public function storageBytes(): ?int
    {
        if (!$this->storage) {
            return null;
        }

        if (preg_match('/^(\d+)\s*(KB|MB|GB|TB)$/i', trim($this->storage), $m)) {
            $num  = (int) $m[1];
            $unit = strtoupper($m[2]);

            return $num * match($unit) {
                'TB' => 1024 ** 4,
                'GB' => 1024 ** 3,
                'MB' => 1024 ** 2,
                'KB' => 1024,
                default => 1,
            };
        }

        return (int) $this->storage;
    }

    /**
     * Monthly file upload limit as integer.
     */
    public function monthlyFileLimit(): ?int
    {
        return $this->monthly_file !== null ? (int) $this->monthly_file : null;
    }
}


