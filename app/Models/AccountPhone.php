<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountPhone extends Model
{
  

    // تحديد الجدول المرتبط بالموديل (اختياري إذا كان الاسم هو نفسه "account_ceilings")
    protected $table = 'account_phones';

    // الحقول القابلة للتعديل
    protected $fillable = [
        'phone',
        'account_id',
        'typeName',
      
    ];

    // العلاقة مع جدول Account (الحساب)
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

   
}
