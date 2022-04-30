<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Company;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;



class CandidateControllerTest extends TestCase
{
    use RefreshDatabase;
     
    /** @test */
    public function when_company_contacts_a_candidate_coins_will_be_deducted()
    {
        // // create a company and assign its wallet
        // $company = Company::factory()->create();

        // // assert that the wallet value is 20
        // $this->assertEquals(20, $company->coins);
    }
}
