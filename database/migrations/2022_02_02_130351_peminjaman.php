<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Peminjaman extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('peminjam_admin_id')->nullable();
            $table->string('pengembalian_admin_id')->nullable();
            $table->string('perpanjang_admin_id')->nullable();
            $table->timestamp('tanggal_pinjam')->nullable();
            $table->timestamp('tanggal_perpanjang')->nullable();
            $table->timestamp('tanggal_kembali')->nullable();
            $table->integer('denda')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('peminjaman');
    }
}
