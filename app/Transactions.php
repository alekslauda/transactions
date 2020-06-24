<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    const PAYMENT_TYPES = [
        'cp' => 'Card Payment',
        'ot' => 'Online Transfer',
        't' => 'Transactions',
    ];

    const STATUSES = [
        's' => 'Sent',
        'r' => 'Received',
        'c' => 'Complete',
    ];

    protected $guarded = [];

    protected $appends = ['status_type'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'from_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'to_id');
    }

    public function getPaymentTypeAttribute($value)
    {
        return self::PAYMENT_TYPES[$value];
    }

    public function getStatusAttribute($value)
    {
        return self::STATUSES[$value];
    }

    public static function setStatus($status)
    {
       return self::getStatusType($status);
    }

    private static function getStatusType($status)
    {
        $statusType = array_search($status, self::STATUSES);

        if ($statusType === false) {
            throw new \Exception("Invalid status type: {$statusType}. Valid statuses: ". implode(',', self::STATUSES) ." ");
        }

        return $statusType;
    }

    public function getStatusTypeAttribute()
    {
        return self::getStatusType($this->status);
    }
}
