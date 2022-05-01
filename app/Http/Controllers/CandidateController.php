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
        $companyHiredCandidatesIdsArray = $company->getHiredCandidatesIdsArray();

        return view('candidates.index', [
            'candidates' => $candidates, 
            'coins' => $coins, 
            'companyContactedCandidatesIds' => $contactedCandidatesIdsArray,
            'companyHiredCandidatesIds' => $companyHiredCandidatesIdsArray
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
