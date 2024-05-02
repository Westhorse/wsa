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
        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('slug')->unique();
            $table->string('sub_title')->nullable();
            $table->text('des')->nullable();
            $table->string('type')->nullable();
            $table->boolean('active')->default(1);
            $table->integer('order_id')->default(1);

            $table->boolean('button_one_active')->nullable();
            $table->string('button_text_one')->nullable();
            $table->string('button_style_one')->nullable();
            $table->string('button_route_one')->nullable();
            $table->string('button_icon_one')->nullable();
            $table->boolean('button_link_type_one')->nullable();
            $table->boolean('button_two_active')->nullable();
            $table->string('button_text_two')->nullable();
            $table->string('button_style_two')->nullable();
            $table->string('button_route_two')->nullable();
            $table->string('button_icon_two')->nullable();
            $table->boolean('button_link_type_two')->nullable();

            $table->foreignId('parent_id')->nullable()->constrained('page_sections')->restrictOnDelete();
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
        Schema::dropIfExists('page_sections');
    }
};
