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
        Schema::create('time_slots', function (Blueprint $table) {
            $table->id();
            $table->time('time_from')->nullable();
            $table->time('time_to')->nullable();
            $table->boolean('active')->default(1);
            $table->boolean('default_status')->default(1);
            $table->text('note')->nullable();
            $table->unsignedBigInteger('day_id')->nullable();
            $table->foreign('day_id')->references('id')->on('event_days')->onDelete('cascade');
            $table->unsignedBigInteger('conference_id')->nullable();
            $table->foreign('conference_id')->references('id')->on('conferences')->onDelete('cascade');
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
        Schema::dropIfExists('time_slots');
    }
};
