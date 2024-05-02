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
        Schema::create('settings_networks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('setting_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('network_id')->nullable()->constrained()->nullOnDelete();
            $table->string('value')->nullable();
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
        Schema::dropIfExists('settings_networks');
    }
};
