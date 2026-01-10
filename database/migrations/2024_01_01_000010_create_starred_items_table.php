<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('starred_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('starrable'); // starrable_type, starrable_id (Space or Page)
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'starrable_type', 'starrable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('starred_items');
    }
};
