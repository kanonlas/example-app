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
        Schema::table('users', function (Blueprint $table) {
            // Check if the column exists before dropping
            if (Schema::hasColumn('users', 'type_id')) {
                // Drop the foreign key if it exists
                $table->dropForeign(['type_id']);
                
                // Drop the 'type_id' column
                $table->dropColumn('type_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Re-add the 'type_id' column and foreign key if you want to reverse the migration
            $table->unsignedBigInteger('type_id')->nullable();
            $table->foreign('type_id')->references('id')->on('personality_types')->onDelete('cascade');
        });
    }
};
