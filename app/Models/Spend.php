<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spend extends Model
{
    use HasFactory;

    // تحديد الجدول المرتبط بالموديل (اختياري إذا كان الاسم هو نفسه "spends")
    protected $table = 'spends';

    // الحقول القابلة للتعديل
    protected $fillable = [
        'entry_id',
        'amount',
        'currency_id',
        'from_account_id',
        'to_account_id',
        'receiver',
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

    // العلاقة مع جدول Account (من الحساب)
    public function fromAccount()
    {
        return $this->belongsTo(Account::class, 'from_account_id');
    }

    // العلاقة مع جدول Account (إلى الحساب)
    public function toAccount()
    {
        return $this->belongsTo(Account::class, 'to_account_id');
    }
}
