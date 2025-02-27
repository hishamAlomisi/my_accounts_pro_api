<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountCeiling extends Model
{
    use HasFactory;

    // تحديد الجدول المرتبط بالموديل (اختياري إذا كان الاسم هو نفسه "account_ceilings")
    protected $table = 'account_ceilings';

    // الحقول القابلة للتعديل
    protected $fillable = [
        'amount',
        'account_id',
        'note',
        'currency_id'
    ];

    // العلاقة مع جدول Account (الحساب)
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    // العلاقة مع جدول Currency (العملة)
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
}
