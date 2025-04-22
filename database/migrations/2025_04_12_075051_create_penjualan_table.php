<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenjualanTable extends Migration
{
    public function up(): void
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            $table->dateTime('tanggal_penjualan')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps(); // Pastikan kolom timestamps ada
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
}
