<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('sponsorship_items', function (Blueprint $table) {
            $table->json('features')->nullable();
            $table->float('price')->nullable();
            $table->float('earlybird_price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('sponsorship_items', function (Blueprint $table) {

        });
    }
};
