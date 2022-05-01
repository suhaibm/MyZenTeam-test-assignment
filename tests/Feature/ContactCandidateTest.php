<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Company;
use App\Models\Wallet;
use App\Models\Candidate;
use Illuminate\Foundation\Testing\RefreshDatabase;



class ContactCandidateTest extends TestCase
{
    use RefreshDatabase;
     
    /** @test */
    public function when_a_company_contacts_a_candidate_coins_will_be_deducted()
    {
        // create a company and a candidate
        $company = Company::factory()->create();
        $company->wallet->update(['coins' => 30]);
        $candidate = Candidate::factory()->create();

        $this->post('/candidates-contact', ['candidate_id' => $candidate->id]);

        // assert deducted coins are 5
        $this->assertEquals(25, $company->fresh()->coins);
    }

    /** @test */
    public function when_a_company_contacts_a_candidate_mark_the_candidate_as_contacted()
    {
        // create a company and a candidate
        $company = Company::factory()->create();
        $candidate = Candidate::factory()->create();

        // assert that the company doesn't have any candidtes at the start
        $this->assertEquals(0, $company->candidates()->count());
        $this->post('/candidates-contact', ['candidate_id' => $candidate->id]);

        // assert 
        // the candidate is inserted as a company candidate
        $this->assertEquals(1, $company->candidates()->count());
        // the inserted candidate into the database is the same contacted candidate
        $this->assertEquals($candidate->id, $company->candidates[0]->id);
        // the candidate is marked as contacted in the database
        $this->assertEquals('CONTACTED', $company->candidates[0]->pivot->status);
    }

    /** @test */
    public function when_a_company_contacts_a_candidate_the_company_must_have_sufficient_balance()
    {
        // create a company and a candidate, and intiate the wallet
        $company = Company::factory()->create();
        $company->wallet->update(['coins' => 4]);
        $candidate = Candidate::factory()->create();

        // assert that the company doesn't have any candidtes at the start
        $this->assertEquals(0, $company->candidates()->count());
        $this->post('/candidates-contact', ['candidate_id' => $candidate->id]);

        // assert 
        // the coins are not deducted
        $this->assertEquals(4, $company->fresh()->coins);
        // the candidate is not contacted, thus not added to the database
        $this->assertEquals(0, $company->fresh()->candidates()->count());
    }

    /** @test */
    public function check_that_the_candidate_was_not_contacted_by_the_company_before()
    {
        // create a company and a candidate
        $company = Company::factory()->create();
        $candidate = Candidate::factory()->create();

        // assert that the company doesn't have any candidtes at the start
        $this->assertEquals(0, $company->candidates()->count());
        // contact the first candidate
        $this->post('/candidates-contact', ['candidate_id' => $candidate->id]);
        // assert the the candidate is in the database
        $this->assertEquals($candidate->id, $company->candidates[0]->id);
        // contact the candidate again
        $this->post('/candidates-contact', ['candidate_id' => $candidate->id]);

        // assert that the candidate is not contacted twice
        $this->assertEquals(1, $company->candidates()->count());
    }
}
