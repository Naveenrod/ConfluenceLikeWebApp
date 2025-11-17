<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('space_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('space_id')->constrained('spaces')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('role', ['viewer', 'editor', 'admin'])->default('viewer');
            $table->timestamps();

            $table->unique(['space_id', 'user_id']);
            $table->index('space_id');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('space_permissions');
    }
};

