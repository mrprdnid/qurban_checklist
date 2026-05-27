<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checklist_kandang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hewan_id')->constrained('hewan')->onDelete('cascade');
            $table->boolean('ambil_domba')->default(false);
            $table->timestamp('ambil_domba_at')->nullable();
            $table->boolean('foto_hidup')->default(false);
            $table->timestamp('foto_hidup_at')->nullable();
            $table->boolean('otw_sembelih')->default(false);
            $table->timestamp('otw_sembelih_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checklist_kandang');
    }
};
