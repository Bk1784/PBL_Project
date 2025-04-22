<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangTable extends Migration
{
    public function up()
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id()->comment('ID utama barang');
            $table->string('kode_barang', 20)->unique()->comment('Kode unik barang');
            $table->string('nama_barang', 100)->index()->comment('Nama barang');
            $table->decimal('harga', 15, 2)->unsigned()->comment('Harga satuan barang');
            $table->integer('stok')->unsigned()->default(0)->comment('Jumlah stok tersedia');
            $table->text('deskripsi')->nullable()->comment('Deskripsi lengkap barang');
            $table->string('satuan', 20)->default('pcs')->comment('Satuan barang');
            $table->softDeletes()->comment('Waktu penghapusan data');
            $table->timestamps();
            
            $table->index(['nama_barang', 'kode_barang']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('barang');
    }
}