<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('permission_type', ['view', 'edit', 'delete'])->default('view');
            $table->timestamps();

            $table->unique(['page_id', 'user_id', 'permission_type']);
            $table->index('page_id');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_permissions');
    }
};

