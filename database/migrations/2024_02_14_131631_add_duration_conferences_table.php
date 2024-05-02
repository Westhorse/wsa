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
        Schema::table('conferences', function (Blueprint $table) {
            $table->boolean('early_bird_active')->default(false);
            $table->dateTime('early_bird_end_date')->nullable();
            $table->dateTime('reg_deadline_date')->nullable();
            $table->json('hotel_booking_max_duration')->nullable();
            $table->decimal('eb_member_delegate_price', 10, 2)->nullable();
            $table->decimal('eb_member_spouse_price', 10, 2)->nullable();
            $table->decimal('eb_non_member_delegate_price', 10, 2)->nullable();
            $table->decimal('eb_non_member_spouse_price', 10, 2)->nullable();
            $table->decimal('member_delegate_price', 10, 2)->nullable();
            $table->decimal('member_spouse_price', 10, 2)->nullable();
            $table->decimal('non_member_delegate_price', 10, 2)->nullable();
            $table->decimal('non_member_spouse_price', 10, 2)->nullable();
            $table->json('duration')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('conferences', function (Blueprint $table) {
        });
    }
};
