<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            // Columns matching the SQL definition provided by the user
            $table->increments('id');                  // INT AUTO_INCREMENT PRIMARY KEY
            $table->char('iso', 2);                    // NOT NULL
            $table->string('name', 80);                // NOT NULL
            $table->char('iso3', 3)->nullable();       // DEFAULT NULL
            $table->smallInteger('numcode')->nullable();
            $table->unsignedInteger('phonecode');

            // Indexes / constraints
            $table->unique('iso');
            $table->unique('iso3');
            $table->index('numcode');
            $table->index('phonecode');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
