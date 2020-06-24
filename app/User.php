<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    const ADMIN = 1;
    const USER = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function senderTransactions() {
        return $this->hasMany(Transactions::class, 'from_id');
    }

    public function receiverTransactions() {
        return $this->hasMany(Transactions::class, 'to_id');
    }

    public function getTransactionsAttribute() {
        return $this->senderTransactions->merge($this->receiverTransactions);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function isAdmin()
    {
        return $this->account_type === 1;
    }
}
