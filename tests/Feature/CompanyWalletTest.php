<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Company;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;



class CompanyWalletTest  extends TestCase
{
    use RefreshDatabase;
     
    /** @test */
    public function each_company_has_a_wallet_with_20_coins_by_default()
    {
        // create a company and assign its wallet
        $company = Company::factory()->create();

        // assert that the wallet value is 20
        $this->assertEquals(20, $company->coins);
    }
}
