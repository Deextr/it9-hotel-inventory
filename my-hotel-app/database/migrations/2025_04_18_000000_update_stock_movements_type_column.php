<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get current ENUM values
        $typeColumn = DB::select("SHOW COLUMNS FROM stock_movements WHERE Field = 'type'")[0];
        $typeEnum = $typeColumn->Type;
        
        // If it's an ENUM, modify it to include 'pullout'
        if (strpos($typeEnum, 'enum') === 0) {
            $values = str_replace('enum(', '', $typeEnum);
            $values = substr($values, 0, -1); // Remove closing parenthesis
            $valuesList = explode(',', $values);
            
            // Check if 'pullout' is already included
            $pulloutExists = false;
            foreach ($valuesList as $value) {
                if (trim($value, "'\"") === 'pullout') {
                    $pulloutExists = true;
                    break;
                }
            }
            
            // If not included, add it
            if (!$pulloutExists) {
                $valuesList[] = "'pullout'";
                $newEnum = "enum(" . implode(',', $valuesList) . ")";
                DB::statement("ALTER TABLE stock_movements MODIFY COLUMN type " . $newEnum);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We can't easily remove a value from an ENUM without knowing the original values
        // so we'll leave this empty
    }
}; 