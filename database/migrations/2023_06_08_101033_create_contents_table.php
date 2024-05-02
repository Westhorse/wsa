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
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->text('detail')->nullable();
            $table->boolean('active')->default(1);
            $table->integer('order_id')->default(1);
            $table->foreignId('parent_id')->nullable()->constrained('contents')->restrictOnDelete();
            $table->foreignId('benefit_id')->nullable()->constrained('benefits')->restrictOnDelete();
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
        Schema::dropIfExists('contents');
    }
};
