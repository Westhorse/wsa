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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('des')->nullable();
            $table->string('slug')->unique();
            $table->string('type')->default('events');
            $table->text('short_des')->nullable();
            $table->string('url_text')->nullable();
            $table->string('url_path')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('delegates')->nullable();
            $table->integer('sessions')->nullable();
            $table->integer('companies')->nullable();
            $table->integer('countries')->nullable();
            $table->boolean('featured')->default(0);
            $table->integer('order_id')->nullable();
            $table->boolean('active')->default(1);
            $table->string('city')->nullable();
            $table->integer('duration')->nullable();
            $table->string('venue')->nullable();
            $table->foreignId('country_id')->nullable()->constrained('countries')->restrictOnDelete();
            $table->foreignId('network_id')->nullable()->references('id')->on('networks')->onDelete('cascade');
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
        Schema::dropIfExists('events');
    }
};
