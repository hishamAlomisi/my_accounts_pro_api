<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    use HasFactory;

    // تحديد الجدول المرتبط بالموديل (اختياري إذا كان الاسم هو نفسه "entries")
    protected $table = 'entries';

    // الحقول القابلة للتعديل
    protected $fillable = [
        'document_id',
        'docNumber',
        'date',
        'note',
        'isDepend',
        'user_id'
    ];

    // العلاقة مع جدول Document (المستند)
    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    // العلاقة مع جدول User (المستخدم)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // العلاقة مع جدول Constraint (القيود)
    public function constraints()
    {
        return $this->hasMany(Constraint::class, 'entry_id');
    }
    public function buyCurrencies()
    {
        return $this->hasMany(BuyCurrency::class, 'entry_id');
    }
    public function suyCurrencies()
    {
        return $this->hasMany(SellCurrency::class, 'entry_id');
    }
    public function receives()
    {
        return $this->hasMany(Receive::class, 'entry_id');
    }
    public function spends()
    {
        return $this->hasMany(Spend::class, 'entry_id');
    }
    public function sendTransfers()
    {
        return $this->hasMany(SendTransfer::class, 'entry_id');
    }
    public function receiveTransfers()
    {
        return $this->hasMany(ReceiveTransfer::class, 'entry_id');
    }
}
