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
        Schema::create('event_days', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->boolean('active')->default(true);
            $table->date('date')->nullable(false);
            $table->timestamps();
        });

        Schema::table('event_days', function (Blueprint $table) {
            $table->unsignedBigInteger('conference_id')->nullable();
            $table->foreign('conference_id')->references('id')->on('conferences')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('event_days');
    }
};
