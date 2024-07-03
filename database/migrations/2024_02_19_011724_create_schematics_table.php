<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('schematics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('short_id', 8)->unique();
            $table->string('slug')->unique()->nullable();
            $table->string('name');
            $table->text('description');
            $table->boolean('is_public')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schematics');
    }
};
