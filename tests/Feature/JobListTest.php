<?php

namespace Tests\Feature;

use App\Services\AuthService;
use App\Services\JobService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\Response as HttpResponse;


class JobListTest extends TestCase
{

    use WithFaker;

    public function test_require_login ()
    {
        $response = $this->get(route('jobs.index'));
        $response->assertStatus(HttpResponse::HTTP_FOUND);
    }

    public function test_regular_user_see_only_own_jobs ()
    {
        $customer_service = new AuthService();
        $job_service = new JobService();

        $manager = $customer_service->register($this->faker->name, $this->faker->email, $this->faker->password(8,30), 'MANAGER');
        $regular = $customer_service->register($this->faker->name, $this->faker->email, $this->faker->password(8,30), 'REGULAR');

        $job_manager = $job_service->create('Test', 'Desc Test', $manager->id, true);
        $job_regular = $job_service->create('Test', 'Desc Test', $regular->id, true);

        $response = $this->actingAs($regular)->get(route('jobs.index'));
        $response->assertStatus(HttpResponse::HTTP_OK);

        $data = $response->json();
        $this->assertTrue(count($data['data']) == 1);
    }

    public function test_manager_user_see_all_jobs ()
    {
        $customer_service = new AuthService();
        $job_service = new JobService();

        $manager = $customer_service->register($this->faker->name, $this->faker->email, $this->faker->password(8,30), 'MANAGER');
        $regular = $customer_service->register($this->faker->name, $this->faker->email, $this->faker->password(8,30), 'REGULAR');

        $job_manager = $job_service->create('Test', 'Desc Test', $manager->id, true);
        $job_regular = $job_service->create('Test', 'Desc Test', $regular->id, true);

        $response = $this->actingAs($manager)->get(route('jobs.index'));
        $response->assertStatus(HttpResponse::HTTP_OK);

        $data = $response->json();
        $this->assertTrue(count($data['data']) > 1);
    }
}
