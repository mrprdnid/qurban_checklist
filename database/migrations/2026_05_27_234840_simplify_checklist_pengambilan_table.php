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
        Schema::table('checklist_pengambilan', function (Blueprint $table) {
            $table->dropColumn(['nomor_wa_pemesan', 'data_pengambilan', 'paraf_pengambil', 'diambil_at']);
        });

        Schema::table('checklist_pengambilan', function (Blueprint $table) {
            $table->boolean('kesesuaian_bagian')->default(false)->after('hewan_id');
            $table->timestamp('kesesuaian_bagian_at')->nullable()->after('kesesuaian_bagian');
            $table->boolean('sudah_diambil')->default(false)->after('kesesuaian_bagian_at');
            $table->timestamp('sudah_diambil_at')->nullable()->after('sudah_diambil');
        });
    }

    public function down(): void
    {
        Schema::table('checklist_pengambilan', function (Blueprint $table) {
            $table->dropColumn(['kesesuaian_bagian', 'kesesuaian_bagian_at', 'sudah_diambil', 'sudah_diambil_at']);
        });

        Schema::table('checklist_pengambilan', function (Blueprint $table) {
            $table->string('nomor_wa_pemesan')->nullable();
            $table->text('data_pengambilan')->nullable();
            $table->string('paraf_pengambil')->nullable();
            $table->timestamp('diambil_at')->nullable();
        });
    }
};
