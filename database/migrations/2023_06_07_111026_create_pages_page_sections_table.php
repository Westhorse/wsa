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
        Schema::create('pages_page_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->nullable()->references('id')->on('pages')->onDelete('cascade');
            $table->foreignId('page_section_id')->nullable()->references('id')->on('page_sections')->onDelete('cascade');
            $table->integer('order_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('pages_page_sections');
    }
};
