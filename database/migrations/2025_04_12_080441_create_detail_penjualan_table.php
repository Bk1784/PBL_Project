<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_penjualan', function (Blueprint $table) {
            // Primary Key
            $table->id()->comment('ID unik detail penjualan');
            
            // Foreign Keys
            $table->foreignId('penjualan_id')
                  ->constrained('penjualan')
                  ->onDelete('cascade')
                  ->comment('ID transaksi penjualan');
                  
            $table->foreignId('barang_id')
                  ->constrained('barang')
                  ->onDelete('restrict')
                  ->comment('ID barang yang dibeli');
            
            // Data Transaksi
            $table->integer('jumlah')
                  ->unsigned()
                  ->default(1)
                  ->comment('Jumlah barang yang dibeli');
                  
            $table->decimal('harga_satuan', 15, 2)
                  ->unsigned()
                  ->comment('Harga per unit saat transaksi');
                  
            $table->decimal('diskon', 5, 2)
                  ->unsigned()
                  ->default(0)
                  ->comment('Diskon per item (dalam persen)');
                  
            $table->decimal('subtotal', 15, 2)
                  ->unsigned()
                  ->comment('Total harga setelah diskon');
            
            // Timestamps
            $table->timestamps();
            
            // Indexes
            $table->index(['penjualan_id', 'barang_id'], 'detail_penjualan_index');
            
            // Additional Constraint
            $table->unique(['penjualan_id', 'barang_id'], 'unique_item_per_transaksi');
        });

        // Untuk MySQL, tambahkan calculated column
        if (config('database.default') === 'mysql') {
            \DB::statement('ALTER TABLE detail_penjualan 
                ADD COLUMN subtotal_calculated DECIMAL(15,2) AS (ROUND(harga_satuan * jumlah * (1 - (diskon/100)), 2)) VIRTUAL');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_penjualan');
    }
};