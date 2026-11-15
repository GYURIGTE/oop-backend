<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meetups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('proposed_by')->constrained('users')->cascadeOnDelete();
            $table->string('location', 180);
            $table->dateTime('scheduled_at');
            $table->enum('status', [
                'Proposed',
                'Accepted',
                'Declined',
                'Completed',
                'Cancelled',
            ])->default('Proposed');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['conversation_id', 'scheduled_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meetups');
    }
};
