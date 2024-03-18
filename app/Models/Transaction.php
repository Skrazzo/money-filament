<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'account_id',
        'name',
        'value',
        'income'
    ];

    public function account(){
        return $this->belongsTo(\App\Models\Account::class);
    }

    use HasFactory;
}
