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
        Schema::create('mbanking_and_ewallet', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('refund_id');
            $table->string('nama_penerima');
            $table->enum('bank_ewallet', ['BCA', 'BNI', 'BRI', 'Mandiri', 'CIMB Niaga', 'Danamon', 'Permata', 'BSI', 'OCBC NISP', 'Maybank', 'Panin', 'UOB', 'DBS', 'HSBC', 'Citibank', 'Standard Chartered', 'Gopay', 'OVO', 'DANA', 'LinkAja', 'ShopeePay', 'QRIS']);
            $table->string('nomor_rekening');
            $table->timestamps();

            $table->foreign('refund_id')->references('id')->on('refunds')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mbanking_and_ewallet');
    }
};
