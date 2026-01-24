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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date')->useCurrent();
            $table->string('type', 255)->default('Other');
            $table->text('details');
            $table->decimal('deposit', 15, 2)->default(0.00);
            $table->decimal('withdraw', 15, 2)->default(0.00);
            $table->string('receive_from', 255);
            $table->string('payment_to', 255);
            $table->string('attachment', 255);
            $table->foreignId('user_id')->constrained('users', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('account_id')->constrained('accounts', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('bounce_transaction_id')->nullable()->constrained('transactions', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('bounce_details', 255);
            $table->dateTime('created')->useCurrent();
            $table->dateTime('modified')->nullable();
            $table->boolean('status')->default(true);
            // $table->foreign('user_id')->references('id')->on('users');
            // $table->foreign('account_id')->references('id')->on('accounts');
            // $table->foreign('bounce_transaction_id')->references('id')->on('transactions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
