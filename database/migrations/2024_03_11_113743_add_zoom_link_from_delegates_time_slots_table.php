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
        Schema::table('delegates_time_slots', function (Blueprint $table) {
            $table->integer('table_number')->nullable();
            $table->string('zoom_link')->nullable();
            $table->boolean('is_online')->default(false);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('delegates_time_slots', function (Blueprint $table) {

        });
    }
};
