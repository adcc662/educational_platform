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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('tax_rate_id')->nullable()->constrained()->onDelete('set null');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->timestamp('start_datetime');
            $table->timestamp('end_datetime');
            $table->decimal('duration_hours', 5, 2);
            $table->integer('capacity');
            $table->integer('available_spots');
            $table->decimal('base_price', 10, 2);
            $table->boolean('is_online')->default(false);
            $table->string('meeting_url', 500)->nullable();
            $table->string('meeting_id')->nullable();
            $table->enum('status', ['draft', 'published', 'cancelled', 'completed'])->default('draft');
            $table->timestamps();

            $table->index(['status', 'start_datetime']);
            $table->index('slug');
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
