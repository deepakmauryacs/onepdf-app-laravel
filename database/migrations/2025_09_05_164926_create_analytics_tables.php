<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('analytics_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // nullable for guests
            $table->string('ip')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('device')->nullable();
            $table->string('platform')->nullable();
            $table->string('browser')->nullable();
            $table->string('referrer')->nullable();
            $table->timestamps();
        });

        Schema::create('analytics_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('analytics_sessions')->onDelete('cascade');
            $table->string('event_type'); // "page_view", "pdf_open", "pdf_page_view"
            $table->string('target')->comment('pdf_id')->nullable(); // url or pdf_id
            $table->integer('page_number')->nullable(); // only for PDFs
            $table->integer('duration')->default(0); // time spent in seconds
            $table->timestamps();
        });

        // Schema::create('pdf_documents', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('vendor_id')->constrained('users')->onDelete('cascade');
        //     $table->string('title');
        //     $table->string('file_path');
        //     $table->integer('total_pages');
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('pdf_documents');
        Schema::dropIfExists('analytics_events');
        Schema::dropIfExists('analytics_sessions');
    }
};
