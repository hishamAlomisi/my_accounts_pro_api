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
        
        Schema::create('spends', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entry_id'); // عمود entry
            $table->float('amount'); // عمود المبلغ
            $table->unsignedBigInteger('currency_id'); // عمود العملة
            $table->unsignedBigInteger('from_account_id'); // عمود من الحساب
            $table->unsignedBigInteger('to_account_id'); // عمود إلى الحساب
            $table->string('receiver'); // عمود المستلم
            $table->text('note')->nullable(); // عمود الملاحظات
            $table->timestamps();
    
            // إضافة القيود (Foreign Keys)
            $table->foreign('entry_id')->references('id')->on('entries')->onDelete('cascade');
            $table->foreign('currency_id')->references('id')->on('currencies');
            $table->foreign('from_account_id')->references('id')->on('accounts');
            $table->foreign('to_account_id')->references('id')->on('accounts');
         });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spends');
    }
};
