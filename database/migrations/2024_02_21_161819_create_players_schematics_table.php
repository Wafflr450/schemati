<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('players_schematics', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignUuid('player_id')->references('id')->on('players')->onDelete('cascade');
            $table->foreignUuid('schematic_id')->references('id')->on('schematics')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players_schematics');
    }
};
