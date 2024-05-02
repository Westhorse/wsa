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
        Schema::create('sponsorship_items', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('count')->nullable();

            $table->boolean('active')->default(1);
            $table->boolean('is_infinity')->default(1);
            $table->integer('order_id')->nullable();

            $table->text('description')->nullable();
            $table->text('short_description')->nullable();

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
        Schema::dropIfExists('sponsorship_items');
    }
};
