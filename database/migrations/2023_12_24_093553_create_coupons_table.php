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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->unique(['code', 'conference_id']);
            $table->string('discount_value')->nullable();
            $table->enum('discount_type', ['fixed','percentage'])->default('fixed');
            $table->enum('coupon_type', ['all','delegate','spouse','delegate_spouse','room','sponsorship_item'])->default('all');
            $table->integer('count')->nullable();
            $table->boolean('active')->nullable();
            $table->unsignedBigInteger('conference_id')->nullable();
            $table->foreign('conference_id')->references('id')->on('conferences')->onDelete('cascade');
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
        Schema::dropIfExists('coupons');
    }
};
