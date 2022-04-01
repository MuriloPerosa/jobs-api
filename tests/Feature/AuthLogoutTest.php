<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Str;

class AuthLogoutTest extends TestCase
{
    use WithFaker;

    public function test_logout ()
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

        $login_data = $login->json();
        $logout = $this->get(route('logout'), ['Authorization' => Str::replaceArray('%', [$login_data['token_type'], $login_data['access_token']], '% - %')]);
        $logout->assertStatus(HttpResponse::HTTP_OK);
    }

    public function test_logout_require_token ()
    {
        $logout = $this->get(route('logout'), []);
        $logout->assertStatus(HttpResponse::HTTP_FOUND);
    }
}
