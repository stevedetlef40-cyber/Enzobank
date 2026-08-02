<?php

namespace Tests\Feature;

use Tests\TestCase;

class FrontendLegalPagesTest extends TestCase
{
    public function test_privacy_policy_page_renders()
    {
        $response = $this->get('/link/privacy-policy');
        $response->assertStatus(200);
        $response->assertSee('EnzoBank', false);
        $response->assertSee('Privacy Policy', false);
        $response->assertDontSee('iBanking', false);
    }

    public function test_terms_of_service_page_renders()
    {
        $response = $this->get('/link/terms-of-service');
        $response->assertStatus(200);
        $response->assertSee('EnzoBank', false);
        $response->assertSee('Terms of Service', false);
        $response->assertDontSee('iBanking', false);
    }
}
