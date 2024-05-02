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
        Schema::create('benefits_networks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('benefit_id')->nullable()->references('id')->on('benefits')->onDelete('cascade');
            $table->foreignId('network_id')->nullable()->references('id')->on('networks')->onDelete('cascade');
            $table->boolean('active')->default(0);
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
        Schema::dropIfExists('benefits_networks');
    }
};
