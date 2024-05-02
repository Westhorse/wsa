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
        Schema::create('delegates_dietaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delegate_id')->nullable()->references('id')->on('delegates')->onDelete('cascade');
            $table->foreignId('dietary_id')->nullable()->references('id')->on('dietaries')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('delegates_dietaries');
    }
};
