<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class BankDetailsPageTest extends TestCase
{
    public function test_bank_details_page_renders_with_details()
    {
        $user = User::where('email', 'test-one@enzobank.org')->firstOrFail();
        $response = $this->actingAs($user)->get(route('user.bank.details.index'));
        $response->assertStatus(200);
        $response->assertSee('Bank Details', false);
        $response->assertSee('Your EnzoBank International Details', false);
        $response->assertSee('Copy All Details', false);
    }

    public function test_bank_details_page_renders_without_details()
    {
        $user = User::where('email', 'amrkhaled7331@yahoo.com')->firstOrFail();
        $response = $this->actingAs($user)->get(route('user.bank.details.index'));
        $response->assertStatus(200);
        $response->assertSee('No saved bank accounts yet', false);
    }

    public function test_bank_details_store_validates()
    {
        $user = User::where('email', 'surpport.primesavings@gmail.com')->firstOrFail();
        $response = $this->actingAs($user)->post(route('user.bank.details.store'), []);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['recipient_name', 'bank_name', 'account_number_iban', 'country']);
    }
}
