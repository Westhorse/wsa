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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->json('public_types')->nullable();
            $table->json('features')->nullable();
            $table->text('description')->nullable();
            $table->integer('count')->nullable();
            $table->integer('delegates_count')->nullable();
            $table->float('price')->nullable();
            $table->boolean('public_show')->default(0);
            $table->boolean('active')->default(1);
            $table->integer('order_id')->nullable();
            $table->enum('type', ['single','double','other'])->default('single');
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
        Schema::dropIfExists('rooms');
    }
};
