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
        Schema::create('location_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(0);
            $table->timestamps();
            
            // Ensure a single item can only be assigned once to a location
            $table->unique(['location_id', 'item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_items');
    }
};
