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
        Schema::table('event_items', function (Blueprint $table) {
            $table->string('post_title')->nullable();
            $table->string('sub_title')->nullable();
            $table->boolean('button_active')->default(1);
            $table->json('button')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('event_items', function (Blueprint $table) {

        });
    }
};
