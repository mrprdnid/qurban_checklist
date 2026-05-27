<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hewan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_urut')->unique();
            $table->enum('jenis', ['domba', 'sapi']);
            $table->string('nama_hewan')->nullable();
            $table->string('nama_pekurban');
            $table->string('nomor_wa')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hewan');
    }
};
