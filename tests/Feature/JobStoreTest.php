<?php

namespace Tests\Feature;

use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Http\Response as HttpResponse;

class JobStoreTest extends TestCase
{
    use WithFaker;

    public function test_require_login ()
    {
        $data = [
            'title'       => $this->faker->words(3),
            'description' => $this->faker->sentence(),
            'is_complete' => random_int(0, 1),
            'user_id'     => 1,
        ];

        $response = $this->post(route('jobs.store'), $data);
        $response->assertStatus(HttpResponse::HTTP_FOUND);
    }

    public function test_store_job ()
    {
        $user = (new AuthService())->register($this->faker->name, $this->faker->email, $this->faker->password(8,30), 'MANAGER');

        $data = [
            'title'       => $this->faker->word,
            'description' => $this->faker->sentence(),
            'is_complete' => random_int(0, 1),
            'user_id'     => $user->id,
        ];

        $response = $this->actingAs($user)->post(route('jobs.store'), $data);
        $response->assertStatus(HttpResponse::HTTP_CREATED);
    }

    public function test_user_manager_can_create_jobs_for_others ()
    {
        $customer_service = new AuthService();

        $manager = $customer_service->register($this->faker->name, $this->faker->email, $this->faker->password(8,30), 'MANAGER');
        $regular = $customer_service->register($this->faker->name, $this->faker->email, $this->faker->password(8,30), 'REGULAR');

        $data = [
            'title'       => $this->faker->word,
            'description' => $this->faker->sentence(),
            'is_complete' => random_int(0, 1),
            'user_id'     => $regular->id,
        ];

        $response = $this->actingAs($manager)->post(route('jobs.store'), $data);
        $response->assertStatus(HttpResponse::HTTP_CREATED);

        $response->assertJson(['data' => ['user_id' => $regular->id]]);
    }

    public function test_user_regular_cannot_create_jobs_for_others ()
    {
        $customer_service = new AuthService();

        $manager = $customer_service->register($this->faker->name, $this->faker->email, $this->faker->password(8,30), 'MANAGER');
        $regular = $customer_service->register($this->faker->name, $this->faker->email, $this->faker->password(8,30), 'REGULAR');

        $data = [
            'title'       => $this->faker->word,
            'description' => $this->faker->sentence(),
            'is_complete' => random_int(0, 1),
            'user_id'     => $manager->id,
        ];

        $response = $this->actingAs($regular)->post(route('jobs.store'), $data);
        $response->assertStatus(HttpResponse::HTTP_CREATED);

        $response->assertJson(['data' => ['user_id' => $regular->id]]);
    }

    public function test_title_is_required ()
    {
        $user = (new AuthService())->register($this->faker->name, $this->faker->email, $this->faker->password(8,30), 'MANAGER');

        $data = [
            'title'       => '',
            'description' => $this->faker->sentence(),
            'is_complete' => random_int(0, 1),
            'user_id'     => $user->id,
        ];

        $response = $this->actingAs($user)->post(route('jobs.store'), $data);
        $response->assertStatus(HttpResponse::HTTP_FOUND);
    }

    public function test_title_max_chars ()
    {
        $user = (new AuthService())->register($this->faker->name, $this->faker->email, $this->faker->password(8,30), 'MANAGER');

        $data = [
            'title'       => Str::random(101),
            'description' => $this->faker->sentence(),
            'is_complete' => random_int(0, 1),
            'user_id'     => $user->id,
        ];

        $response = $this->actingAs($user)->post(route('jobs.store'), $data);
        $response->assertStatus(HttpResponse::HTTP_FOUND);
    }

    public function test_description_is_required ()
    {
        $user = (new AuthService())->register($this->faker->name, $this->faker->email, $this->faker->password(8,30), 'MANAGER');

        $data = [
            'title'       => $this->faker->word,
            'description' => '',
            'is_complete' => random_int(0, 1),
            'user_id'     => $user->id,
        ];

        $response = $this->actingAs($user)->post(route('jobs.store'), $data);
        $response->assertStatus(HttpResponse::HTTP_FOUND);
    }

    public function test_description_max_chars ()
    {
        $user = (new AuthService())->register($this->faker->name, $this->faker->email, $this->faker->password(8,30), 'MANAGER');

        $data = [
            'title'       => $this->faker->word,
            'description' => Str::random(501),
            'is_complete' => random_int(0, 1),
            'user_id'     => $user->id,
        ];

        $response = $this->actingAs($user)->post(route('jobs.store'), $data);
        $response->assertStatus(HttpResponse::HTTP_FOUND);
    }

    public function test_is_complete_is_required ()
    {
        $user = (new AuthService())->register($this->faker->name, $this->faker->email, $this->faker->password(8,30), 'MANAGER');

        $data = [
            'title'       => $this->faker->word,
            'description' => $this->faker->sentence(),
            'user_id'     => $user->id,
        ];

        $response = $this->actingAs($user)->post(route('jobs.store'), $data);
        $response->assertStatus(HttpResponse::HTTP_FOUND);
    }

    public function test_user_id_is_required ()
    {
        $user = (new AuthService())->register($this->faker->name, $this->faker->email, $this->faker->password(8,30), 'MANAGER');

        $data = [
            'title'       => $this->faker->word,
            'description' => Str::random(20),
            'is_complete' => random_int(0, 1),
            'user_id'     => 1900,
        ];

        $response = $this->actingAs($user)->post(route('jobs.store'), $data);
        $response->assertStatus(HttpResponse::HTTP_FOUND);
    }

    public function test_user_id_exists ()
    {
        $user = (new AuthService())->register($this->faker->name, $this->faker->email, $this->faker->password(8,30), 'MANAGER');

        $data = [
            'title'       => $this->faker->word,
            'description' => Str::random(20),
            'is_complete' => random_int(0, 1),
        ];

        $response = $this->actingAs($user)->post(route('jobs.store'), $data);
        $response->assertStatus(HttpResponse::HTTP_FOUND);
    }
}
