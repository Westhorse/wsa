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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('label')->nullable();
            $table->string('placeholder')->nullable();
            $table->string('des')->nullable();
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->string('data')->nullable();
            $table->string('class')->nullable();
            $table->string('rules')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('settings')->nullOnDelete();
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
        Schema::dropIfExists('settings');
    }
};
