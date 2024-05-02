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
        Schema::create('event_section_pages', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('slug')->unique();
            $table->string('post_title')->nullable();
            $table->string('title')->nullable();
            $table->string('sub_title')->nullable();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->string('video_url')->nullable();
            $table->json('button_one')->nullable();
            $table->json('button_two')->nullable();
            $table->json('divider')->nullable();
            $table->boolean('default')->default(1);
            $table->boolean('button_one_active')->default(1);
            $table->boolean('button_two_active')->default(1);
            $table->boolean('active')->default(1);
            $table->integer('order_id')->nullable();
            $table->foreignId('event_page_id')->nullable()->constrained()->nullOnDelete();
            $table->softDeletes();
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
        Schema::dropIfExists('event_section_pages');
    }
};
