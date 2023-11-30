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
        Schema::create('materis', function (Blueprint $table) {
            $table->id('id_materi');
            $table->string('judul_materi');
            $table->string('url_materi');
            $table->string('deskripsi_materi');
            $table->unsignedBigInteger('id_topik');
            $table->unsignedBigInteger('id_kategori');
            $table->foreign('id_topik')->references('id_topik')->on('topik');
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materis');
    }
};
