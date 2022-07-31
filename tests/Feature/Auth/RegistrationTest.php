<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_register()
    {
        $this->postJson(route('user.register'), ['name' => 'Joao', 'email' => 'joao@gmail.com', 'password' => '123456', 'password_confirmation' => '123456'])->assertCreated();

        $this->assertDatabaseHas('users', ['name' => 'Joao']);
    }
}
