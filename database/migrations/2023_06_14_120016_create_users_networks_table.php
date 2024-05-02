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
        Schema::create('users_networks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('network_id')->nullable()->references('id')->on('networks')->onDelete('cascade');
            $table->enum('type', ['member', 'founder', 'vendor', 'partner'])->default('member');
            $table->enum('status', ['pending', 'approved', 'suspended', 'blacklisted'])->default('pending');
            $table->boolean('network')->default(0);
            $table->boolean('active')->default(0);
            $table->boolean('fpp')->default(0);
            $table->date('expire_date')->nullable();
            $table->date('start_date')->nullable();
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
        Schema::dropIfExists('users_networks');
    }
};
