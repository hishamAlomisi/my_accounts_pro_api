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
    
       
        Schema::create('receives', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('entry_id'); // عمود ID تلقائي الزيادة
            $table->float('amount'); // عمود المبلغ (float)
            $table->unsignedBigInteger('currency_id'); // عمود العملة
            $table->unsignedBigInteger('from_account_id'); // عمود الحساب المرسل
            $table->unsignedBigInteger('to_account_id'); // عمود الحساب المستلم
            $table->string('receiver'); // عمود الاسم المستلم
            $table->text('note')->nullable(); // عمود الملاحظات (اختياري)
            $table->timestamps(); // أعمدة created_at و updated_at
    
            // إضافة القيود (Constraints)
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
        Schema::dropIfExists('receives');
    }
};
