<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_plans', function (Blueprint $table) {
            $table->string('payment_provider', 50)->nullable()->after('status');
            $table->string('payment_reference', 100)->nullable()->after('payment_provider');
            $table->decimal('payment_amount', 10, 2)->nullable()->after('payment_reference');
            $table->string('payment_currency', 3)->nullable()->after('payment_amount');
            $table->string('payment_status', 25)->nullable()->after('payment_currency');
        });
    }

    public function down(): void
    {
        Schema::table('user_plans', function (Blueprint $table) {
            $table->dropColumn([
                'payment_provider',
                'payment_reference',
                'payment_amount',
                'payment_currency',
                'payment_status',
            ]);
        });
    }
};
