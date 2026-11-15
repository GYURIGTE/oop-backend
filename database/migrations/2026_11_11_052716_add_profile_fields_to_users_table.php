<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name', 80)->nullable()->after('name');
            $table->string('last_name', 80)->nullable()->after('first_name');
            $table->string('username', 30)->nullable()->unique()->after('last_name');
            $table->string('phone', 30)->nullable()->after('email');
            $table->text('bio')->nullable()->after('phone');
            $table->string('location', 120)->nullable()->after('bio');
            $table->string('avatar_url')->nullable()->after('location');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn([
                'first_name',
                'last_name',
                'username',
                'phone',
                'bio',
                'location',
                'avatar_url',
            ]);
        });
    }
};
