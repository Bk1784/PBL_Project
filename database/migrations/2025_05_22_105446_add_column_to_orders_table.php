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
         Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }

            if (!Schema::hasColumn('orders', 'courier')) {
                $table->string('courier')->nullable();
            }

            if (!Schema::hasColumn('orders', 'invoice_no')) {
                $table->string('invoice_no')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'payment_method')) {
                $table->dropColumn('payment_method');
            }

            if (Schema::hasColumn('orders', 'courier')) {
                $table->dropColumn('courier');
            }

            if (Schema::hasColumn('orders', 'invoice_no')) {
                $table->dropColumn('invoice_no');
            }
        });
    }
};
