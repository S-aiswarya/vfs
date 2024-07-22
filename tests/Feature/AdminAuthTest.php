<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{

    public function test_admin_login_page_can_be_renderd_with_url_specified_in_admin_config(): void
    {
        $admin_url = config()->get('admin.url');
        $response = $this->get($admin_url);
        $response->assertStatus(200);
    }

    public function test_can_send_otp_to_email_address(): void
    {

    }
}
