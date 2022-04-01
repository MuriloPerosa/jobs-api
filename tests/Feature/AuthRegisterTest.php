<?php

namespace Tests\Feature;

use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response as HttpResponse;
use Tests\TestCase;

class AuthRegisterTest extends TestCase
{

    use WithFaker;

    public function test_register_new_user()
    {
        $roles = ['MANAGER', 'REGULAR'];
        $data = [
            'name'     => $this->faker->name(),
            'email'    => $this->faker->email(),
            'password' => $this->faker->password(8, 30),
            'role'     =>  $roles[array_rand($roles)]
        ];

        $response = $this->post(route('auth.register'), $data);
        $response->assertStatus(HttpResponse::HTTP_CREATED);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'role',
                'created_at',
            ]
        ]);
    }

    public function test_name_is_required()
    {
        $roles = ['MANAGER', 'REGULAR'];
        $data = [
            'name'     => '',
            'email'    => $this->faker->email(),
            'password' => $this->faker->password(8, 30),
            'role'     =>  $roles[array_rand($roles)]
        ];

        $response = $this->post(route('auth.register'), $data);
        $response->assertStatus(HttpResponse::HTTP_FOUND);
    }

    public function test_name_max_chars()
    {
        $roles = ['MANAGER', 'REGULAR'];
        $data = [
            'name'     => $this->faker->text(256),
            'email'    => $this->faker->email(),
            'password' => $this->faker->password(8, 30),
            'role'     =>  $roles[array_rand($roles)]
        ];

        $response = $this->post(route('auth.register'), $data);
        $response->assertStatus(HttpResponse::HTTP_FOUND);
    }

    public function test_email_is_required()
    {
        $roles = ['MANAGER', 'REGULAR'];
        $data = [
            'name'     => $this->faker->name(),
            'email'    => '',
            'password' => $this->faker->password(8, 30),
            'role'     =>  $roles[array_rand($roles)]
        ];

        $response = $this->post(route('auth.register'), $data);
        $response->assertStatus(HttpResponse::HTTP_FOUND);
    }

    public function test_email_format()
    {
        $roles = ['MANAGER', 'REGULAR'];
        $data = [
            'name'     => $this->faker->name(),
            'email'    => 'wrong_mail_format',
            'password' => $this->faker->password(8, 30),
            'role'     =>  $roles[array_rand($roles)]
        ];

        $response = $this->post(route('auth.register'), $data);
        $response->assertStatus(HttpResponse::HTTP_FOUND);
    }

    public function test_email_unique()
    {
        $roles = ['MANAGER', 'REGULAR'];

        $email = $this->faker->email();

        $data = [
            'name'     => $this->faker->name(),
            'email'    => $email,
            'password' => $this->faker->password(8, 30),
            'role'     => $roles[array_rand($roles)]
        ];

        $response = $this->post(route('auth.register'), $data);
        $response->assertStatus(HttpResponse::HTTP_CREATED);

        $response2 = $this->post(route('auth.register'), $data);
        $response2->assertStatus(HttpResponse::HTTP_BAD_REQUEST);
    }

    public function test_password_is_required()
    {
        $roles = ['MANAGER', 'REGULAR'];
        $data = [
            'name'     => $this->faker->name(),
            'email'    => $this->faker->email(),
            'password' => '',
            'role'     =>  $roles[array_rand($roles)]
        ];

        $response = $this->post(route('auth.register'), $data);
        $response->assertStatus(HttpResponse::HTTP_FOUND);
    }

    public function test_password_min_chars()
    {
        $roles = ['MANAGER', 'REGULAR'];
        $data = [
            'name'     => $this->faker->name(),
            'email'    => $this->faker->email(),
            'password' => '1',
            'role'     =>  $roles[array_rand($roles)]
        ];

        $response = $this->post(route('auth.register'), $data);
        $response->assertStatus(HttpResponse::HTTP_FOUND);
    }

    public function test_password_max_chars()
    {
        $roles = ['MANAGER', 'REGULAR'];
        $data = [
            'name'     => $this->faker->name(),
            'email'    => $this->faker->email(),
            'password' => $this->faker->password(31, 32),
            'role'     =>  $roles[array_rand($roles)]
        ];

        $response = $this->post(route('auth.register'), $data);
        $response->assertStatus(HttpResponse::HTTP_FOUND);
    }

    public function test_role_is_required()
    {
        $data = [
            'name'     => $this->faker->name(),
            'email'    => $this->faker->email(),
            'password' => $this->faker->password(8, 30),
            'role'     => ''
        ];

        $response = $this->post(route('auth.register'), $data);
        $response->assertStatus(HttpResponse::HTTP_FOUND);
    }

    public function test_role_accept_manager()
    {
        $data = [
            'name'     => $this->faker->name(),
            'email'    => $this->faker->email(),
            'password' => $this->faker->password(8, 30),
            'role'     => 'MANAGER'
        ];

        $response = $this->post(route('auth.register'), $data);
        $response->assertStatus(HttpResponse::HTTP_CREATED);
    }

    public function test_role_accept_regular()
    {
        $data = [
            'name'     => $this->faker->name(),
            'email'    => $this->faker->email(),
            'password' => $this->faker->password(8, 30),
            'role'     => 'REGULAR'
        ];

        $response = $this->post(route('auth.register'), $data);
        $response->assertStatus(HttpResponse::HTTP_CREATED);
    }

    public function test_role_not_accept_other_values()
    {
        $data = [
            'name'     => $this->faker->name(),
            'email'    => $this->faker->email(),
            'password' => $this->faker->password(8, 30),
            'role'     => 'OTHER'
        ];

        $response = $this->post(route('auth.register'), $data);
        $response->assertStatus(HttpResponse::HTTP_FOUND);
    }
}
