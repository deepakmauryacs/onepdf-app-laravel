<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->unsignedInteger('lead_form_id')->nullable()->index()->after('document_id');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('lead_form_id');
        });
    }
};
