<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellCurrency extends Model
{
    use HasFactory;

    // تحديد الجدول المرتبط بالموديل (اختياري إذا كان الاسم هو نفسه "sell_currencies")
    protected $table = 'sell_currencies';

    // الحقول القابلة للتعديل
    protected $fillable = [
        'entry_id',
        'amount',
        'price',
        'currency_id',
        'mcAmount',
        'mc_currency_id',
        'account_id',
        'note'
    ];

    // العلاقة مع جدول Entry (المستند)
    public function entry()
    {
        return $this->belongsTo(Entry::class, 'entry_id');
    }

    // العلاقة مع جدول Currency (العملة)
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    // العلاقة مع جدول Account (الحساب)
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    // العلاقة مع جدول Currency (العملة المكافئة)
    public function mcCurrency()
    {
        return $this->belongsTo(Currency::class, 'mc_currency_id');
    }
}
