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
        Schema::create('account_ceilings', function (Blueprint $table) {
            $table->id();
            $table->float('amount'); // عمود المبلغ
            $table->unsignedBigInteger('account_id'); // عمود الحساب
            $table->text('note')->nullable(); // عمود الملاحظات (اختياري)
            $table->unsignedBigInteger('currency_id'); // عمود العملة
            $table->timestamps();
    
            // إضافة القيود (Foreign Keys)
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('currency_id')->references('id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_ceilings');
    }
};
