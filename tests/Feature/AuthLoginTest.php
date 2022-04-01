<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response as HttpResponse;
use Tests\TestCase;

class AuthLoginTest extends TestCase
{

    use WithFaker;

    public function test_login_correct_credentials ()
    {
        $credentials = ['email' => $this->faker->email(), 'password' => $this->faker->password(8, 30)];

        $roles = ['MANAGER', 'REGULAR'];
        $data = [
            'name'     => $this->faker->name(),
            'email'    => $credentials['email'],
            'password' => $credentials['password'],
            'role'     => $roles[array_rand($roles)]
        ];

        $register = $this->post(route('auth.register'), $data);
        $register->assertStatus(HttpResponse::HTTP_CREATED);

        $login = $this->post(route('login', $credentials));
        $login->assertStatus(HttpResponse::HTTP_OK);

        $login->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'role',
                'created_at',
            ],
            'access_token',
            'token_type',
            'expires_in'
        ]);
    }

    public function test_login_wrong_credentials ()
    {
        $credentials = ['email' => 'fake@mail.com', 'password' => $this->faker->password(8, 30)];

        $login = $this->post(route('login', $credentials));
        $login->assertStatus(HttpResponse::HTTP_UNAUTHORIZED);
    }

    public function test_email_is_required ()
    {
        $credentials = ['email' => '', 'password' => $this->faker->password(8, 30)];

        $login = $this->post(route('login', $credentials));
        $login->assertStatus(HttpResponse::HTTP_FOUND);
    }

    public function test_email_format ()
    {
        $credentials = ['email' => 'wrong_mail_format', 'password' => $this->faker->password(8, 30)];

        $login = $this->post(route('login', $credentials));
        $login->assertStatus(HttpResponse::HTTP_FOUND);
    }

    public function test_password_is_required ()
    {
        $credentials = ['email' => $this->faker->email(), 'password' => ''];


        $login = $this->post(route('login', $credentials));
        $login->assertStatus(HttpResponse::HTTP_FOUND);
    }
}
