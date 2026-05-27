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
        Schema::create('checklist_kehadiran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hewan_id')->constrained('hewan')->onDelete('cascade');
            $table->boolean('absensi')->default(false);
            $table->timestamp('absensi_at')->nullable();
            $table->boolean('penyerahan_tagging')->default(false);
            $table->timestamp('penyerahan_tagging_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklist_kehadiran');
    }
};
