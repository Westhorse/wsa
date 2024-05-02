<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            // Add a unique constraint on the combination of 'slug' and 'network_id'
            $table->unique(['slug', 'network_id']);
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            // Drop the unique constraint if you need to roll back the migration
            $table->dropUnique(['slug', 'network_id']);
        });
    }
};
