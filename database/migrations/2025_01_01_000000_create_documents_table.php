<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('filename');                 // original, sanitized
            $table->string('filepath');                 // public/uploads/{use_id}/stored.ext (relative from public/)
            $table->unsignedBigInteger('size')->default(0);
            $table->string('share_token', 64)->nullable()->unique();
            $table->timestamp('share_expires_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('documents');
    }
};
