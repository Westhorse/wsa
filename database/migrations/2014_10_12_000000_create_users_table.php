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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('wsa_id')->unique()->nullable(); // WSA ID number is unique
            $table->string('name')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('map_long')->nullable();
            $table->string('map_lat')->nullable();
            $table->string('slogan')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('company_email')->nullable();
            $table->string('email'); // Login Email
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('website')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('unhashed_password')->nullable();
            $table->unique(['password', 'email']);
            $table->longText('profile')->nullable();
            $table->string('branches')->nullable();
            $table->string('business_est')->nullable();
            $table->string('employees_num')->nullable();
            $table->string('ref_value')->nullable();
            $table->string('other_certificates')->nullable();
            $table->string('other_services')->nullable();
            $table->enum('type_company', ['hq', 'branch'])->nullable()->default(null);
            $table->boolean('tos_acceptance')->default(false);
            $table->integer('phone_key_id')->nullable();
            $table->integer('fax_key_id')->nullable();
            $table->boolean('role')->default(1);
            $table->unique(['unhashed_password', 'email']);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
