<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tag_callbacks', function (Blueprint $table) {
            $table->id();
            $table->uuid('tag_id');
            $table->string('callback_url');
            $table->string('event_type');
            $table->string('callback_format')->default('json');
            $table->unsignedBigInteger('created_by_user_id');
            $table->boolean('is_active')->default(true);
            $table->datetime('last_triggered_at')->nullable();
            $table->json('headers')->nullable();
            $table->text('payload_template')->nullable();
            $table->timestamps();

            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
            $table->foreign('created_by_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tag_callbacks');
    }
};
