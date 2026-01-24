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
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name', 255);
            $table->text('address');
            $table->string('phone', 255);
            $table->string('mobile', 255);
            $table->string('fax', 255);
            $table->string('email', 255);
            $table->string('website', 255);
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
        Schema::dropIfExists('banks');
    }
};
