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
        Schema::create('sub_menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->nullable()->constrained('menus')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('link')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('active')->default(1);
            $table->boolean('type')->default(1);
            $table->integer('order_id')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('sub_menus')->onDelete('cascade');
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
        Schema::dropIfExists('sub_menus');
    }
};
