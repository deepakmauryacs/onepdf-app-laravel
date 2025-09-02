<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('links', function (Blueprint $table) {
            // If you really need MyISAM, uncomment:
            // $table->engine = 'MyISAM';

            $table->increments('id');
            $table->unsignedInteger('document_id')->index();
            $table->string('slug', 20)->unique();
            $table->json('permissions');
            $table->timestamp('created_at')->useCurrent();

            // Recommended (if you keep InnoDB):
            // $table->foreign('document_id')
            //       ->references('id')->on('documents')
            //       ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('links');
    }
};
