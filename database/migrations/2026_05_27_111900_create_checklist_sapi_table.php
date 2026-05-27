<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checklist_sapi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hewan_id')->constrained('hewan')->onDelete('cascade');
            $table->boolean('foto_hidup')->default(false);
            $table->timestamp('foto_hidup_at')->nullable();
            $table->boolean('video_sembelih')->default(false);
            $table->timestamp('video_sembelih_at')->nullable();
            $table->boolean('bagian_pekurban')->default(false);
            $table->timestamp('bagian_pekurban_at')->nullable();
            $table->boolean('kesesuaian_bagian')->default(false);
            $table->timestamp('kesesuaian_bagian_at')->nullable();
            $table->boolean('otw_pengambilan')->default(false);
            $table->timestamp('otw_pengambilan_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checklist_sapi');
    }
};
