<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $guarded = [];

    const DEFAULT_COINS = 20;
    

    public static function boot() {
        parent::boot();

        static::created(function($company) {
            $company->createWalletWithDefaultValue();
        });
    }
    

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function getCoinsAttribute()
    {
        return $this->wallet->coins ?? 0;
    }

    private function createWalletWithDefaultValue()
    {
        $this->wallet()->create(['coins' => Company::DEFAULT_COINS]);
    }

}
