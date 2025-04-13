<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('floor_number');
            $table->enum('area_type', ['room', 'kitchen', 'hallway', 'restaurant', 'storage', 'other']);
            $table->string('room_number')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Create a unique constraint for the combination
            $table->unique(['floor_number', 'area_type', 'room_number']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('locations');
    }
};
