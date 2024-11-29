<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('model_histories', function (Blueprint $table) {
            $table->id();
            $table->string('model_type'); // Model class name
            $table->unsignedBigInteger('model_id'); // Primary key of the model
            $table->string('action'); // add, edit, delete
            $table->json('old_data')->nullable(); // Data before update/delete
            $table->json('new_data')->nullable(); // Data after create/update
            $table->unsignedBigInteger('user_id')->nullable(); // User performing the action
            $table->string('note', '255')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_histories');
    }
};
