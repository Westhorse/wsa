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
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('sub_title')->nullable();
            $table->string('des')->nullable();

            $table->boolean('button_one_active')->default(0);
            $table->string('button_text_one')->nullable();
            $table->string('button_route_one')->nullable();
            $table->string('button_style_one')->nullable();
            $table->string('button_icon_one')->nullable();
            $table->boolean('button_link_type_one')->default(1);

            $table->boolean('button_two_active')->default(0);
            $table->string('button_text_two')->nullable();
            $table->string('button_style_two')->nullable();
            $table->string('button_route_two')->nullable();
            $table->string('button_icon_two')->nullable();
            $table->boolean('button_link_type_two')->default(1);

            $table->boolean('active')->default(true);
            $table->integer('order_id')->nullable();
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
        Schema::dropIfExists('sliders');
    }
};
