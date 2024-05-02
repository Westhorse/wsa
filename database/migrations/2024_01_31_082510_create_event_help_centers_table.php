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
        Schema::create('event_help_centers', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('sub_title')->nullable();
            $table->string('post_title')->nullable();
            $table->string('type')->nullable();
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->json('list')->nullable();
            $table->string('slug')->nullable();
            $table->boolean('active')->default(1);
            $table->integer('order_id')->nullable();
            $table->foreignId('parent_id')->nullable()->references('id')->on('event_help_centers')->onDelete('cascade');
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
        Schema::dropIfExists('event_help_centers');
    }
};
