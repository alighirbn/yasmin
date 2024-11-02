<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('classifications', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Name of the classification (e.g., "Great Location")
            $table->decimal('price_per_meter', 15, 0)->default(0); // Price per meter
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('classifications');
    }
};
