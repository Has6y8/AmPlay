<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('watch_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('anime_id');
            $table->string('episode_id');
            $table->string('anime_title')->nullable();
            $table->string('episode_number')->nullable();
            $table->string('poster_url')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('watch_histories'); }
};