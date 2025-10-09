<?php

use Illuminate\Support\Facades\DB;
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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // Disable checks
        Schema::table('queues', function (Blueprint $table) {
            $table->foreignId('type_id')->constrained()->onDelete('cascade');
        });
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // Re-enable checks

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('queues', function (Blueprint $table) {
            $table->dropColumn('type_id');
        });
    }
};
