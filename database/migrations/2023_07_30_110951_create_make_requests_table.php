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
        Schema::create('make_requests', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('commodity')->nullable();
            $table->string('freight_terms')->nullable();
            $table->string('movement')->nullable();
            $table->string('service_at')->nullable();
            $table->string('origin_zipcode')->nullable();
            $table->string('destination_zipcode')->nullable();
            $table->string('origin_address')->nullable();
            $table->string('destination_address')->nullable();
            $table->longText('description')->nullable();
            $table->string('company')->nullable();
            $table->integer('package_number')->nullable();
            $table->decimal('gross_weight', 10, 2)->nullable();
            $table->decimal('volume', 10, 2)->nullable();
            $table->decimal('size_length', 10, 2)->nullable();
            $table->decimal('size_width', 10, 2)->nullable();
            $table->decimal('size_height', 10, 2)->nullable();
            $table->boolean('insurance')->nullable();
            $table->boolean('active')->default(1);
            $table->foreignId('origin_country_id')->nullable()->constrained('countries')->restrictOnDelete();
            $table->foreignId('destination_country_id')->nullable()->constrained('countries')->restrictOnDelete();
            $table->foreignId('enum_tables_id')->nullable()->constrained('enum_tables')->restrictOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->restrictOnDelete();
            $table->integer('order_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('make_requests');
    }
};

