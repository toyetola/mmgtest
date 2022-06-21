<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_to_id', 'wallet_from_id', 'user_id', 'amount'
    ];

    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function wallet1Involved(){
        return $this->belongsTo('App\Models\Wallet', 'wallet_from_id', 'id');
    }

    public function wallet2Involved(){
        return $this->belongsTo('App\Models\Wallet', 'wallet_to_id', 'id');
    }
}
