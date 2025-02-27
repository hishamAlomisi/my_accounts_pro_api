<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SendTransfer extends Model
{
    use HasFactory;

    // تحديد الجدول المرتبط بالموديل (اختياري إذا كان الاسم هو نفسه "send_transfers")
    protected $table = 'send_transfers';

    // الحقول القابلة للتعديل
    protected $fillable = [
        'entry_id',
        'amount',
        'currency_id',
        'transferComm',
        'transfer_comm_currency_id',
        'receiverName',
        'receiverPhone',
        'senderName',
        'senderPhone',
        'note',
        'account_id',
        'agent_account_id',
        'agentComm',
        'agent_comm_currency_id',
        'transferNumber',
        'status',
        'target',
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

    // العلاقة مع جدول Currency (عملة العمولة)
    public function transferCommCurrency()
    {
        return $this->belongsTo(Currency::class, 'transfer_comm_currency_id');
    }

    // العلاقة مع جدول Account (الحساب المحول)
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    // العلاقة مع جدول Account (حساب الوكيل)
    public function agentAccount()
    {
        return $this->belongsTo(Account::class, 'agent_account_id');
    }

    // العلاقة مع جدول Currency (عملة عمولة الوكيل)
    public function agentCommCurrency()
    {
        return $this->belongsTo(Currency::class, 'agent_comm_currency_id');
    }
}
