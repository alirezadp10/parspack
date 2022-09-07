<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function user_can_register_in_application()
    {
        $this->postJson('/api/register', [
            'name'                  => 'john doe',
            'email'                 => 'foo@bar.com',
            'password'              => '123456',
            'password_confirmation' => '123456'
        ])->assertCreated()->assertJson($data = [
            "name"  => "john doe",
            "email" => "foo@bar.com",
        ]);

        $this->assertDatabaseHas('users', $data);
    }

    /**
     * @test
     */
    public function user_cannot_send_short_pass_for_registering()
    {
        $this->postJson('/api/register', [
            'name'                  => 'john doe',
            'email'                 => 'foo@bar.com',
            'password'              => '1234',
            'password_confirmation' => '1234'
        ])->assertUnprocessable();

        $this->assertDatabaseMissing('users', [
            "name"  => "john doe",
            "email" => "foo@bar.com",
        ]);
    }

    /**
     * @test
     */
    public function user_cannot_send_pass_which_different_from_confirmation_pass_for_registering()
    {
        $this->postJson('/api/register', [
            'name'                  => 'john doe',
            'email'                 => 'foo@bar.com',
            'password'              => '123456',
            'password_confirmation' => '654321'
        ])->assertUnprocessable();

        $this->assertDatabaseMissing('users', [
            "name"  => "john doe",
            "email" => "foo@bar.com",
        ]);
    }

    /**
     * @test
     */
    public function user_cannot_send_invalid_email_for_registering()
    {
        $this->postJson('/api/register', [
            'name'                  => 'john doe',
            'email'                 => 'foobarcom',
            'password'              => '123456',
            'password_confirmation' => '123456'
        ])->assertUnprocessable();

        $this->assertDatabaseMissing('users', [
            "name"  => "john doe",
            "email" => "foobarcom",
        ]);
    }

    /**
     * @test
     */
    public function user_cannot_send_email_which_already_registered_for_registering()
    {
        User::factory()->create(['email' => 'foo@bar.com']);

        $this->postJson('/api/register', [
            'name'                  => 'john doe',
            'email'                 => 'foo@bar.com',
            'password'              => '123456',
            'password_confirmation' => '123456'
        ])->assertUnprocessable();

        $this->assertDatabaseCount('users', 1);
    }

    /**
     * @test
     */
    public function user_can_login_in_application()
    {
        $user = User::factory()->create(['password' => bcrypt('123456')]);

        $this->postJson('/api/login', [
            'email'    => $user->email,
            'password' => '123456',
        ])->assertOk()->assertJsonStructure(['access_token', 'token_type', 'user']);
    }

    /**
     * @test
     */
    public function user_cannot_login_in_application_with_invalid_credential()
    {
        $user = User::factory()->create(['password' => bcrypt('123456')]);

        $this->postJson('/api/login', [
            'email'    => $user->email,
            'password' => '654321',
        ])->assertUnauthorized();
    }
}