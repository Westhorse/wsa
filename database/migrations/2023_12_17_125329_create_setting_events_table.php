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
        Schema::create('setting_events', function (Blueprint $table) {
            $table->id();
            $table->string('label')->nullable();
            $table->string('placeholder')->nullable();
            $table->string('des')->nullable();
            $table->string('name')->unique();
            $table->string('type')->nullable();
            $table->string('data')->nullable();
            $table->string('class')->nullable();
            $table->string('rules')->nullable();
            $table->longText('value')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('setting_events')->nullOnDelete();
            $table->json('button')->nullable();
            $table->json('items')->nullable();
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
        Schema::dropIfExists('setting_events');
    }
};
