<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Company extends Model
{
    use HasFactory;
    protected $guarded = [];

    const DEFAULT_COINS = 20;
    const CONTACT_COINS_COST = 5;
    const HIRED_STATUS = 'HIRED';
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
        // check constraints
        $this->validateContactCandidateConstraints($candidate);
        //register the candidate as contacted in the database
        $this->candidates()->attach($candidate, ['status' => COMPANY::CONTACTED_STATUS]);
        // deduct the coins
        $this->wallet->update(['coins' => $this->coins - Company::CONTACT_COINS_COST]);
        //send email
        $this->sendContactCandidateEmail($candidate);
    }

    public function hireCandidate($candidate) 
    {
        $this->validateHireCandidateConstraints($candidate);
        // hire candidate
        $this->candidates()->attach($candidate, ['status' => Company::HIRED_STATUS]);
        $this->wallet->update(['coins' => $this->coins + Company::CONTACT_COINS_COST]);
        $this->sendHireCandidateEmail($candidate);
    }

    public function getContactedCandidatesIdsArray()
    {
        return $this->candidates()->where('status', Company::CONTACTED_STATUS)->get()->pluck('id')->toArray();
    }

    public function getHiredCandidatesIdsArray()
    {
        return $this->candidates()->where('status', Company::HIRED_STATUS)->get()->pluck('id')->toArray();
    }

    private function createWalletWithDefaultValue()
    {
        $this->wallet()->create(['coins' => Company::DEFAULT_COINS]);
    }

    private function validateContactCandidateConstraints($candidate)
    {
         // check that the candidate wasn't contacted by the company before
         $contactedCandidatesIdsArray = $this->getContactedCandidatesIdsArray();
         if (in_array($candidate->id, $contactedCandidatesIdsArray))
            abort(400, "The request is invalid");
 
         // check if company has enough coins to contact
         if($this->wallet->coins < Company::CONTACT_COINS_COST)
            abort(400, "The request is invalid");
    }

    private function validateHireCandidateConstraints($candidate)
    {
        // check if the candidate has been contacted by the same company first
        if (! in_array($candidate->id, $this->getContactedCandidatesIdsArray()))
            abort(400, "The request is invalid");

        // if the candidate is hired by another company, don't hire
        if($candidate->isHired())
            abort(400, "The request is invalid");
    }

    private function sendContactCandidateEmail($candidate)
    {
        $companyName = $this->name;
        $subject = "Invite to an interview at {$companyName}";
        $body = 'We are pleased to invite you to an interview.';
        $this->sendEmailToCandidate($companyName,$this->email, $candidate, $subject, $body);
    }

    private function sendHireCandidateEmail($candidate)
    {
        $companyName = $this->name;
        $subject = "Congratulations on being hired at {$companyName}";
        $body = 'We are pleased to inform you that you have been hired.';
        $this->sendEmailToCandidate($companyName,$this->email, $candidate, $subject, $body);
    }

    private function sendEmailToCandidate($companyName, $companyEmail, $candidate, $subject, $body)
    {
        
        Mail::raw($body, function($message) use ($companyName, $companyEmail, $candidate, $subject){ 
            $message->to($candidate->email)
                ->subject($subject)
                ->from($companyEmail, $companyName);
        });
    }

}
