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
        Schema::create('conferences', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('venue')->nullable();
            $table->foreignId('city_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('country_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('virtual')->default(0);
            $table->boolean('active')->default(1);
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
        Schema::dropIfExists('conferences');
    }
};
