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
        Schema::create('entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_id'); // عمود ID المستند
            $table->integer('docNumber'); // عمود رقم المستند
            $table->dateTime('date'); // عمود التاريخ
            $table->text('note')->nullable(); // عمود الملاحظات (اختياري)
            $table->boolean('isDepend'); // عمود حالة الاعتماد
            $table->unsignedBigInteger('user_id'); // عمود المستخدم
            $table->timestamps();
            // إضافة القيود (Foreign Keys)
            $table->foreign('document_id')->references('id')->on('documents');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entries');
    }
};
