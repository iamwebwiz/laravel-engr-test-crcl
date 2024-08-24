<?php

use App\Models\Batch;
use App\Models\Hmo;
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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignIdFor(Hmo::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Batch::class)->nullable()->constrained()->cascadeOnDelete();
            $table->string('provider_name');
            $table->dateTime('encounter_date');
            $table->decimal('total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeignIdFor(Hmo::class);
            $table->dropForeignIdFor(Batch::class);
            $table->dropColumn(['provider_name', 'encounter_date', 'total', 'hmo_id', 'batch_id']);
        });
    }
};
