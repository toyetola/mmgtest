<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'current_balance', 'name', 'type', 'user_id', 'unique_identifier', 'minimum_balance'
    ];

    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function transactions(){
        return $this->belongsToMany(Transaction::class);
    }


}
