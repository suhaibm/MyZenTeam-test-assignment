<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $guarded = [];

    const DEFAULT_COINS = 20;
    const CONTACT_COINS_COST = 5;
    const CONTACTED_STATUS = 'CONTACTED';
    

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

    public function candidates()
    {
        return $this->belongsToMany(Candidate::class, 'hiring_process')->withPivot(['status', 'created_at', 'updated_at']);
    }

    public function contactCandidate($candidate)
    {
        // check that the candidate wasn't contacted by the company before

        // check if company has enough coins to contact

        //do the contact
        $this->candidates()->attach($candidate, ['status' => COMPANY::CONTACTED_STATUS]);

        // deduct the coins
        $this->wallet->update(['coins' => $this->coins - Company::CONTACT_COINS_COST]);
    }

    private function createWalletWithDefaultValue()
    {
        $this->wallet()->create(['coins' => Company::DEFAULT_COINS]);
    }

}
