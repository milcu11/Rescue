<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    public function test_site_root_shows_login_page(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Sign in to start your session');
    }

    public function test_public_donate_page_is_accessible(): void
    {
        $response = $this->get('/donate');

        $response->assertStatus(200);
        $response->assertSee('Donate');
    }

    public function test_login_page_does_not_show_public_donation_links(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertDontSee('Donate to RescuePH');
        $response->assertDontSee('Track an existing donation');
    }
}
