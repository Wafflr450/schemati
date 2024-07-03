<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('schematic_tag', function (Blueprint $table) {
            $table->uuid('schematic_id');
            $table->uuid('tag_id');
            $table->timestamps();

            $table->primary(['schematic_id', 'tag_id']);

            $table->foreign('schematic_id')->references('id')->on('schematics')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('schematic_tag');
    }
};
