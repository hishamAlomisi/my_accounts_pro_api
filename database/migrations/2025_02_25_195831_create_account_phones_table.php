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
        Schema::create('account_phones', function (Blueprint $table) {
            $table->id();
            $table->string('phone'); // عمود الهاتف
            $table->string('typeName'); // عمود نوع الهاتف
            $table->unsignedBigInteger('account_id'); // عمود الحساب
            $table->timestamps();
    
            // إضافة القيود (Foreign Key)
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_phones');
    }
};
