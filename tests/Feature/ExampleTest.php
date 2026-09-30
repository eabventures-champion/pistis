<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_guest_redirected_to_customer_login_from_account(): void
    {
        $response = $this->get('/account');
        $response->assertRedirect(route('customer.login'));
    }

    public function test_guest_redirected_to_admin_login_from_admin(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect(route('admin.login'));
    }

    public function test_standard_login_route_exists_and_redirects(): void
    {
        $this->assertTrue(\Illuminate\Support\Facades\Route::has('login'));
        $response = $this->get(route('login'));
        $response->assertRedirect('/login');
    }
}
