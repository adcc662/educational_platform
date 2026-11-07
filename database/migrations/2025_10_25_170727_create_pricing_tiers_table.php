<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pricing_tiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->integer('min_quantity');
            $table->integer('max_quantity')->nullable()->comment('Null means no upper limit');
            $table->decimal('discount_percentage', 5, 2);
            $table->timestamps();

            $table->index('course_id');
        });

        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        // Tiers of the same course must not share any quantity.
        DB::statement('CREATE EXTENSION IF NOT EXISTS btree_gist');
        DB::statement('ALTER TABLE pricing_tiers ADD CONSTRAINT pricing_tiers_quantity_range_check CHECK (min_quantity >= 1 AND (max_quantity IS NULL OR max_quantity >= min_quantity))');
        DB::statement("ALTER TABLE pricing_tiers ADD CONSTRAINT pricing_tiers_no_overlap EXCLUDE USING gist (course_id WITH =, int4range(min_quantity, max_quantity, '[]') WITH &&)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_tiers');
    }
};
