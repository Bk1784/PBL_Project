<?php

// use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Support\Facades\Schema;

// class CreateRefundsTable extends Migration
// {
//     /**
//      * Run the migrations.
//      */
//     public function up(): void
//     {
//         Schema::create('refunds', function (Blueprint $table) {
//             $table->id();
//             $table->unsignedBigInteger('order_id')->unique();
//             $table->unsignedBigInteger('user_id');
//             $table->text('refund_reason');
//             $table->text('reject_reason')->nullable();
//             $table->enum('status', ['pending', 'rejected', 'accepted', 'completed'])->default('pending');
//             $table->timestamp('refunded_at')->nullable();
//             $table->timestamps();

//             $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
//             $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
//         });
//     }

//     /**
//      * Reverse the migrations.
//      */
//     public function down(): void
//     {
//         Schema::dropIfExists('refunds');
//     }
// }


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->unique();
            $table->unsignedBigInteger('user_id');
            $table->text('refund_reason');
            $table->text('reject_reason')->nullable();
            $table->enum('status', ['pending', 'rejected', 'accepted', 'completed'])->default('pending');
            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
}
