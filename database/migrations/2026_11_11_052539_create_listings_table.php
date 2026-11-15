<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('category', 80);
            $table->text('description')->nullable();
            $table->unsignedInteger('price')->default(0);
            $table->string('condition', 30)->default('Used');
            $table->string('location', 120)->nullable();
            $table->enum('status', ['Active', 'Sold', 'Draft'])->default('Active');
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();

            $table->index(['status', 'category']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
