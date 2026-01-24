<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users_new', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 255);
            $table->string('last_name', 255);
            $table->text('address');
            $table->string('phone', 255);
            $table->string('mobile', 255);
            $table->string('email', 255);
            $table->string('password', 255);
            $table->date('date_of_birth')->nullable();
            $table->date('joining_date')->nullable();
            $table->string('image', 255);
            $table->string('facebook', 255);
            $table->string('twitter', 255);
            $table->string('instagram', 255);
            $table->string('google_plus', 255);
            $table->string('linkedin', 255);
            $table->string('company_ids', 255);
            $table->string('user_type', 255)->default('user');
            $table->dateTime('created')->useCurrent();
            $table->dateTime('modified')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_new');
    }
};
