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
        Schema::table('food', function (Blueprint $table) {
            // Expand measure column
            $table->string('measure', 100)->change();
            
            // Add nutrition columns if they don't exist
            if (!Schema::hasColumn('food', 'grams')) {
                $table->float('grams')->default(0);
            }
            if (!Schema::hasColumn('food', 'calories')) {
                $table->float('calories')->default(0);
            }
            if (!Schema::hasColumn('food', 'protein')) {
                $table->float('protein')->default(0);
            }
            if (!Schema::hasColumn('food', 'fat')) {
                $table->float('fat')->default(0);
            }
            if (!Schema::hasColumn('food', 'sat_fat')) {
                $table->float('sat_fat')->default(0);
            }
            if (!Schema::hasColumn('food', 'fiber')) {
                $table->float('fiber')->default(0);
            }
            if (!Schema::hasColumn('food', 'carbs')) {
                $table->float('carbs')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('food', function (Blueprint $table) {
            $table->dropColumnIfExists(['grams', 'calories', 'protein', 'fat', 'sat_fat', 'fiber', 'carbs']);
        });
    }
};
