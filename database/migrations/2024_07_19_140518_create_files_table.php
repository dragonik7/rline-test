<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('size');
            $table->timestamp('uploaded_at');
            $table->boolean('is_public');
            $table->foreignId('user_id');
            $table->foreignId('directory_id')->nullable();
            $table->string('unique_link')->nullable();
            $table->string('path');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('files');
    }
};
