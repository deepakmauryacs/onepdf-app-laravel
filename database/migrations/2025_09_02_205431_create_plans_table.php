<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->decimal('inr_price', 10, 2);
            $table->decimal('usd_price', 10, 2);
            $table->enum('billing_cycle', ['free','month','year']);
            $table->string('storage', 190)->nullable(); // added to match your SQL dump
            $table->string('monthly_file', 190)->nullable(); // added to match your SQL dump
            $table->timestamps();

            $table->unique(['name', 'billing_cycle']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
