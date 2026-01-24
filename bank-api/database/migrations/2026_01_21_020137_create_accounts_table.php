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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_name', 255);
            $table->string('account_number', 255);
            $table->foreignId('company_id')->constrained('companies', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('bank_id')->constrained('banks', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('branch_id')->constrained('branches', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->text('cheque_book');
            $table->decimal('opening_balance', 15, 2)->default(0.00);
            $table->dateTime('created')->useCurrent();
            $table->dateTime('modified')->nullable();
            $table->boolean('status')->default(true);
            // $table->foreign('company_id')->references('id')->on('companies');
            // $table->foreign('bank_id')->references('id')->on('banks');
            // $table->foreign('branch_id')->references('id')->on('branches');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
