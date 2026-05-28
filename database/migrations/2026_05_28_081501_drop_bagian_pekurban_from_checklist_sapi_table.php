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
        Schema::table('checklist_sapi', function (Blueprint $table) {
            $table->dropColumn(['bagian_pekurban', 'bagian_pekurban_at']);
        });
    }

    public function down(): void
    {
        Schema::table('checklist_sapi', function (Blueprint $table) {
            $table->boolean('bagian_pekurban')->default(false)->after('mulai_seset_at');
            $table->timestamp('bagian_pekurban_at')->nullable()->after('bagian_pekurban');
        });
    }
};
