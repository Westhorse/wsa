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
        Schema::create('delegates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->string('job_title')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('unhashed_password')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('order_id')->nullable()->references('id')->on('orders')->onDelete('cascade');
            $table->foreignId('tshirt_size_id')->nullable()->constrained()->nullOnDelete();
            $table->string('phone')->nullable();
            $table->string('cell')->nullable();
            $table->integer('phone_key_id')->nullable();
            $table->integer('cell_key_id')->nullable();
            $table->text('extra_dietaries')->nullable();
            $table->string('type')->nullable();
            $table->foreignId('delegate_id')->nullable()->constrained('delegates')->onDelete('cascade');
            $table->unsignedBigInteger('conference_id')->nullable();
            $table->foreign('conference_id')->references('id')->on('conferences')->onDelete('cascade');
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
        Schema::dropIfExists('delegates');
    }
};
