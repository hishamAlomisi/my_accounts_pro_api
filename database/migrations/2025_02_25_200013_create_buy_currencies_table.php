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
        Schema::create('buy_currencies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entry_id'); // عمود entry
            $table->float('amount'); // عمود المبلغ
            $table->float('price'); // عمود السعر
            $table->unsignedBigInteger('currency_id'); // عمود العملة
            $table->float('mcAmount'); // عمود المبلغ المكافئ
            $table->unsignedBigInteger('mc_currency_id'); // عمود العملة المكافئة
            $table->unsignedBigInteger('account_id'); // عمود الحساب
            $table->text('note')->nullable(); // عمود الملاحظات
            $table->timestamps();
    
            // إضافة القيود (Foreign Keys)
            $table->foreign('entry_id')->references('id')->on('entries')->onDelete('cascade');
            $table->foreign('currency_id')->references('id')->on('currencies');
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->foreign('mc_currency_id')->references('id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buy_currencies');
    }
};
