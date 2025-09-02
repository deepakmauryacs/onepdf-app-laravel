<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('partnership_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email', 150);
            $table->string('contact_number', 32);
            $table->text('message');
            $table->timestamp('created_at')->useCurrent();

            $table->index('email');
            $table->index('created_at');
            $table->engine = 'InnoDB';
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partnership_messages');
    }
};
