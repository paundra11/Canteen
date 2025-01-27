<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'username',
        'access_id',
        'full_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function gravatar($size=150){
        return  "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $this->email ) ) ) . "?d=mp&s=" . $size;
    }
    public function access(){
        return $this->belongsTo(Acces::class);
    }
    public function wallet(){
        return $this->hasOne(Wallet::class);
    }
    public function rfid(){
        return $this->HasOneThrough(Rfid::class, Wallet::class,'user_id','wallet_id');
    }
    public function canteen(){
        return $this->hasOne(Canteen::class);
    }
    public function myorders(){
        return $this->hasMany(Order::class);
    }
    public function cashouts()
    {
        return $this->hasMany(Cashout::class);
    }

    public function getTotalCashoutsAttribute()
    {
        return $this->cashouts->sum('amount');
    }
    // User.php
    public function totalOrders()
    {
    if ($this->access_id == 1) {
        return Order::count();
    } elseif ($this->access_id == 2 && $this->canteen) {
        return $this->canteen->orders->count();
    }

    return 0;
}

    // public function rfid(){
    //     return $this->hasOne(Rfid::class);
    // }
}
