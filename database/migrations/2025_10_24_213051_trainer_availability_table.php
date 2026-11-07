<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Weekly recurring slots and one-off date overrides are stored separately,
     * so neither table carries columns that depend on another non-key column.
     */
    public function up(): void
    {
        Schema::create('trainer_weekly_availability', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainer_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('day_of_week')->comment('0 = Sunday, 6 = Saturday');
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();

            $table->index(['trainer_id', 'day_of_week']);
        });

        Schema::create('trainer_availability_overrides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainer_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->time('start_time')->nullable()->comment('Null means the whole day');
            $table->time('end_time')->nullable()->comment('Null means the whole day');
            $table->boolean('is_available')->default(false);
            $table->timestamps();

            $table->index(['trainer_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainer_availability_overrides');
        Schema::dropIfExists('trainer_weekly_availability');
    }
};
