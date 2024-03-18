<?php

namespace App\Models;

use App\Observers\AccountObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(AccountObserver::class)]
class Account extends Model
{
    protected $fillable = [
        'user_id',
        'name',
    ];

    public function user(){
        return $this->belongsTo(\App\Models\User::class);
    }

    public function transaction(){
        return $this->belongsTo(\App\Models\Transaction::class);
    }

    protected static function booted(): void
    {
        static::addGlobalScope('user', function (Builder $query) {
            if (auth()->check()) {
                $query->where('user_id', auth()->user()->id);
            }
        });
    }

    protected $casts = [
        'name' => 'encrypted',
    ];

    use HasFactory;
}
