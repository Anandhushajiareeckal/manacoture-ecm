<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_storefront_root_returns_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
