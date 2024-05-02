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
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->string('company')->nullable();
            $table->foreignId('country_id')->nullable()->constrained('countries')->restrictOnDelete();
            $table->text('des')->nullable();
            $table->text('short_des')->nullable();
            $table->integer('order_id')->nullable();
            $table->boolean('active')->default(1);
            $table->boolean('show_home')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
