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
            $table->boolean('mulai_seset')->default(false)->after('video_sembelih_at');
            $table->timestamp('mulai_seset_at')->nullable()->after('mulai_seset');
        });
    }

    public function down(): void
    {
        Schema::table('checklist_sapi', function (Blueprint $table) {
            $table->dropColumn(['mulai_seset', 'mulai_seset_at']);
        });
    }
};
