<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('link_analytics', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('link_id')->index();
            $table->string('event', 32)->index();         // e.g. "view", "page"
            $table->json('meta')->nullable();             // optional: page no, etc.
            $table->string('ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();

            // If using InnoDB, you can enforce FK:
            // $table->foreign('link_id')->references('id')->on('links')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('link_analytics');
    }
};
