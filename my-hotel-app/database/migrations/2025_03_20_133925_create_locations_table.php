<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('floor_number');
            $table->enum('area_type', ['room', 'kitchen', 'hallway', 'restaurant', 'storage', 'other']);
            $table->integer('room_number')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Create a unique constraint for the combination
            $table->unique(['floor_number', 'area_type', 'room_number']);
        });
        
        // If you have existing data, you'll need to convert string room numbers to integers
        // Get existing locations with room numbers
        $locations = DB::table('locations')->whereNotNull('room_number')->get();
        
        foreach ($locations as $location) {
            // Extract numeric value from room number
            preg_match('/(\d+)/', $location->room_number, $matches);
            $numericValue = isset($matches[1]) ? (int)$matches[1] : null;
            
            // Update the location with the numeric room number
            DB::table('locations')->where('id', $location->id)->update([
                'room_number' => $numericValue
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('locations');
    }
};
