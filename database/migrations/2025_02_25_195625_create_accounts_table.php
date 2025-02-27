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
            $table->string('name'); // عمود الاسم
            $table->unsignedBigInteger('account_type_id'); // عمود نوع الحساب
            $table->string('address')->nullable(); // عمود العنوان (اختياري)
            $table->text('note')->nullable(); // عمود الملاحظات (اختياري)
            $table->string('ceilingAlert')->nullable(); // عمود التنبيه
            $table->integer('role'); // عمود الدور
            $table->unsignedBigInteger('user_id'); // عمود المستخدم (مفتاح أجنبي)
            $table->timestamps();
            // إضافة القيود (Foreign Keys)
            $table->foreign('account_type_id')->references('id')->on('account_types')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
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
