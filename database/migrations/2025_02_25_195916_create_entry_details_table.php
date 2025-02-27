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
    
        Schema::create('entry_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entry_id'); // عمود entry
            $table->float('amount'); // عمود المبلغ
            $table->unsignedBigInteger('currency_id'); // عمود العملة
            $table->float('mcAmount'); // عمود المبلغ المكافئ
            $table->unsignedBigInteger('account_id'); // عمود الحساب
            $table->text('note')->nullable(); // عمود الملاحظات (اختياري)
            $table->timestamps();
    
            // إضافة القيود (Foreign Keys)
            $table->foreign('entry_id')->references('id')->on('entries')->onDelete('cascade');
            $table->foreign('currency_id')->references('id')->on('currencies');
            $table->foreign('account_id')->references('id')->on('accounts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entry_details');
    }
};
