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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->nullable();
            $table->float('total')->nullable();
            $table->float('amount')->nullable();
            $table->string('stripe_pi')->nullable();
            $table->string('stripe_ch')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('package_id')->nullable()->constrained('packages')->nullOnDelete();
            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->nullOnDelete();
            $table->foreignId('conference_id')->nullable()->constrained('conferences')->nullOnDelete();
            $table->enum('status', ['in_application_form','pending_payment','pending_bank_transfer','approved_online_payment' , 'approved_bank_transfer'])->default('in_application_form');

            $table->float('total_price_delegate')->nullable();
            $table->float('total_price_package')->nullable();
            $table->float('total_price_spouse')->nullable();
            $table->float('total_price_sponsorship_items')->nullable();
            $table->float('total_price_rooms')->nullable();

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
        Schema::dropIfExists('orders');
    }
};
