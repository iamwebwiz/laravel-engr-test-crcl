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
        if (! Schema::hasColumns('hmos', ['batch_strategy', 'email'])) {
            Schema::table('hmos', function (Blueprint $table) {
                $table->string('batching_strategy')->nullable();
                $table->string('email')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumns('hmos', ['batch_strategy', 'email'])) {
            Schema::table('hmos', function (Blueprint $table) {
                $table->dropColumn(['batching_strategy', 'email']);
            });
        }
    }
};
