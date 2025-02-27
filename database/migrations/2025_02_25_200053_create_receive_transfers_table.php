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
       
        Schema::create('receive_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entry_id')->constrained('entries')->onDelete('cascade'); // معرّف الدخول
            $table->decimal('amount', 15, 2); // المبلغ المستلم
            $table->foreignId('currency_id')->constrained('currencies'); // العملة
            $table->decimal('transferComm', 15, 2); // عمولة التحويل
            $table->foreignId('transfer_comm_currency_id')->constrained('currencies'); // العملة التي يتم حساب العمولة بها
            $table->string('receiverName'); // اسم المستلم
            $table->string('receiverPhone'); // رقم هاتف المستلم
            $table->string('senderName'); // اسم المرسل
            $table->string('senderPhone'); // رقم هاتف المرسل
            $table->text('note')->nullable(); // ملاحظات التحويل
            $table->foreignId('account_id')->constrained('accounts'); // الحساب المستلم
            $table->foreignId('agent_account_id')->constrained('accounts'); // حساب الوكيل
            $table->string('transferNumber'); // رقم التحويل
            $table->enum('status', ['1', '0'])->default('1'); // حالة التحويل
            $table->string('target'); // الوجهة

            // إضافة التواريخ
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receive_transfers');
    }
};
