<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
  
    

            // Columns
            $table->increments('id');                     // INT(11) AUTO_INCREMENT
            $table->char('iso', 2);                       // NOT NULL
            $table->string('name', 80);                   // NOT NULL
            $table->string('nicename', 80);               // NOT NULL
            $table->char('iso3', 3)->nullable();          // DEFAULT NULL
            $table->smallInteger('numcode')->nullable();  // DEFAULT NULL
            $table->integer('phonecode')->unsigned();     // NOT NULL

            // Indexes / constraints (optional but useful)
            $table->unique('iso');
            $table->unique('iso3');       // iso3 is unique per country; remove if you expect duplicates
            $table->index('numcode');
            $table->index('phonecode');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('country');
    }
};
