<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('customers', function (Blueprint $table) {
        $table->id();
        $table->string('nama');
        $table->string('username')->nullable();
        $table->string('email')->unique();
        $table->string('kontak')->nullable();
        $table->string('alamat')->nullable();
        $table->string('jenis_kelamin')->nullable();
        $table->text('bio')->nullable();
        $table->string('photo')->nullable();
        $table->string('password');
        $table->rememberToken();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};