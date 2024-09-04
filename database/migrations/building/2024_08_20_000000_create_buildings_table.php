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
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id_create')->nullable();
            $table->foreign('user_id_create')->references('id')->on('users');

            $table->unsignedBigInteger('user_id_update')->nullable();
            $table->foreign('user_id_update')->references('id')->on('users');

            $table->unsignedBigInteger('building_category_id')->nullable();
            $table->foreign('building_category_id')->references('id')->on('building_category');

            $table->unsignedBigInteger('building_type_id')->nullable();
            $table->foreign('building_type_id')->references('id')->on('building_type');

            $table->string('url_address', '60')->unique()->nullable();
            $table->string('building_number', '10');
            $table->string('house_number', '10');
            $table->string('block_number', '10');
            $table->string('building_area', '10');
            $table->string('building_map_x', '6')->nullable();
            $table->string('building_map_y', '6')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buildings');
    }
};
