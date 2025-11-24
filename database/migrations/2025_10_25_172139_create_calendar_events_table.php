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
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->string('eventable_type')->comment('Polymorphic: Course, Reservation');
            $table->unsignedBigInteger('eventable_id');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('provider', ['google', 'outlook', 'zoom', 'teams']);
            $table->string('external_event_id')->nullable();
            $table->string('title');
            $table->timestamp('start_datetime');
            $table->timestamp('end_datetime');
            $table->enum('sync_status', ['pending', 'synced', 'failed'])->default('pending');
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();

            $table->index(['eventable_type', 'eventable_id']);
            $table->index(['user_id', 'provider']);
            $table->index('sync_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};
