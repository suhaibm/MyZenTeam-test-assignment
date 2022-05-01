<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidate;
use App\Models\Company;

class CandidateController extends Controller
{
    public function index(){
        $candidates = Candidate::all();
        $company = Company::find(1);
        $coins = $company->coins;
        $contactedCandidatesIdsArray = $company->getContactedCandidatesIdsArray();
        $companyHiredCandidatesIds = $company->candidates()->where('status', 'HIRED')->get()->pluck('id');

        return view('candidates.index', [
            'candidates' => $candidates, 
            'coins' => $coins, 
            'companyContactedCandidatesIds' => $contactedCandidatesIdsArray,
            'companyHiredCandidatesIds' => $companyHiredCandidatesIds->toArray()
        ]);
    }

    public function contact(Request $request)
    {
        $candidate = Candidate::find($request->candidate_id);

        $company = Company::find(1);
        $company->contactCandidate($candidate);
    }

    public function hire(Request $request)
    {
        $candidate = Candidate::find($request->candidate_id);
        
        $company = Company::find(1);
        $company->hireCandidate($candidate);
    }
}
