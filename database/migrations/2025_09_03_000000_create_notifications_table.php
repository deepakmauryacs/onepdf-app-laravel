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
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->enum('audience', ['user', 'all'])->default('user');
            $table->string('title');
            $table->text('message');
            $table->enum('type', ['info','success','warning','error','system'])->default('info');
            $table->unsignedTinyInteger('priority')->default(1);
            $table->string('action_url', 1024)->nullable();
            $table->boolean('is_read')->default(false);
            $table->dateTime('read_at')->nullable();
            $table->json('metadata')->nullable();
            $table->dateTime('created_at')->useCurrent();

            $table->index(['audience','user_id','is_read','created_at'], 'idx_user_unread_created');
            $table->index('created_at', 'idx_created');
            $table->index('user_id', 'idx_user');

            $table->foreign('user_id', 'fk_notifications_user')
                ->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};

