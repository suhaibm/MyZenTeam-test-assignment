<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidate;
use App\Models\Company;

class CandidateController extends Controller
{
    public function index(){
    $candidates = Candidate::all();
    $coins = Company::find(1)->coins;
    return view('candidates.index', compact('candidates', 'coins'));
}

    public function contact(Request $request)
    {
        $candidate = Candidate::find($request->candidate_id);

        $company = Company::find(1);
        $company->contactCandidate($candidate);
    }

    public function hire()
    {
        // @todo
        // Your code goes here...
    }
}
