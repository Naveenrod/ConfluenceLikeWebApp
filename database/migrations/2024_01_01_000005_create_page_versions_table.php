<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->onDelete('cascade');
            $table->string('title');
            $table->longText('content');
            $table->integer('version_number');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('change_summary')->nullable();
            $table->timestamps();

            $table->index('page_id');
            $table->index('user_id');
            $table->index('version_number');
            $table->unique(['page_id', 'version_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_versions');
    }
};

