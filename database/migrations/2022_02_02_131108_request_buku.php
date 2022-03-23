<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RequestBuku extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_buku', function (Blueprint $table) {
            $table->id();
            $table->string('judul'); 
            $table->string('nama_pengarang'); 
            $table->string('penerbit'); 
            $table->string('cover_buku'); 
            $table->string('tahun_terbit'); 
            $table->integer('stock'); 
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
        Schema::dropIfExists('request_buku');
    }
}
