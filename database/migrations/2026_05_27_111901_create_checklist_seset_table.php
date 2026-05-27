<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checklist_seset', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hewan_id')->constrained('hewan')->onDelete('cascade');
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
        Schema::dropIfExists('checklist_seset');
    }
};
