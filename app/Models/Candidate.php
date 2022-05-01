<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;
    protected $guarded = [];

    const HIRED_STATUS = 'HIRED';
    const CONTACTED_STATUS = 'CONTACTED';

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'hiring_process')->withPivot(['status', 'created_at', 'updated_at']);
    }

    public function isHired(){
        return $this->companies()
        ->where('status', Candidate::HIRED_STATUS)
        ->get()->pluck('id')->count() > 0? true:false;
    }
}
