<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Company;
use App\Models\Wallet;
use App\Models\Candidate;
use Illuminate\Foundation\Testing\RefreshDatabase;



class HireCandidateTest extends TestCase
{
    use RefreshDatabase;
     
    /** @test */
    public function when_a_company_hires_a_candidate_coins_will_be_added()
    {
        // create a company and a candidate
        $company = Company::factory()->create();
        $company->wallet->update(['coins' => 30]);
        $candidate = Candidate::factory()->create();

        $this->post('/candidates-contact', ['candidate_id' => $candidate->id]);
        // assert deducted coins are 5
        $this->assertEquals(25, $company->fresh()->coins);

        $this->post('/candidates-hire', ['candidate_id' => $candidate->id]);
        // assert added coins are 5
        $this->assertEquals(30, $company->fresh()->coins);
    }

    /** @test */
    public function when_a_company_hires_a_candidate_mark_the_candidate_as_hired()
    {
        // create a company and a candidate
        $company = Company::factory()->create();
        $candidate = Candidate::factory()->create();

        // assert that the company doesn't have any candidtes at the start
        $this->assertEquals(0, count($company->getHiredCandidatesIdsArray()));
        $this->post('/candidates-contact', ['candidate_id' => $candidate->id]);
        $this->post('/candidates-hire', ['candidate_id' => $candidate->id]);

        // assert 
        // the candidate is registered in the database as hired
        $this->assertEquals(1, count($company->fresh()->getHiredCandidatesIdsArray()));
        // // the hired candidate into the database is the same contacted candidate
        $this->assertEquals($candidate->id, $company->fresh()->getHiredCandidatesIdsArray()[0]);
        // // the candidate is marked as hired in the database
        $this->assertEquals('HIRED', $company->fresh()->candidates[1]->pivot->status);
    }

    /** @test */
    public function a_candidate_can_only_be_hired_after_they__have_been_contacted_by_the_same_company()
    {
        // create a company and a candidate, and intiate the wallet
        $company = Company::factory()->create();
        $candidate = Candidate::factory()->create();

        // assert that the company doesn't have any candidtes at the start
        $this->assertEquals(0, count($company->fresh()->getHiredCandidatesIdsArray()));
        $this->post('/candidates-hire', ['candidate_id' => $candidate->id]);

        // assert 
        // the candidate is not hired
        $this->assertEquals(0, count($company->fresh()->getHiredCandidatesIdsArray()));
    }

    /** @test */
    public function a_candidate_can_be_hired_by_only_one_company()
    {
        // create 2 companies and a candidate
        $company1 = Company::factory()->create();
        $company2 = Company::factory()->create();
        $candidate = Candidate::factory()->create();

        $company2->contactCandidate($candidate);
        $company2->hireCandidate($candidate);
        // the post request will by default use company id=1
        $this->post('/candidates-contact', ['candidate_id' => $candidate->id]);
        $this->post('/candidates-hire', ['candidate_id' => $candidate->id]);

        // assert that the candidate will not be hired by company1, as company2 has hired him first
        $this->assertEquals(1, $company1->candidates()->count()); // 1 entry (contacted)
        $this->assertEquals(2, $company2->candidates()->count()); // 2 entries (contacted, hired)
    }
}
