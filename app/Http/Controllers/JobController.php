<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobStoreRequest;
use App\Http\Resources\JobResource;
use App\Services\JobService;
use Illuminate\Http\Request;

class JobController extends Controller
{

    /**
     * @var App\Services\JobService
     */
    private $job_service;

    /**
     * Constructor
     * @param $job_service dependency injection
     */
    public function __construct(JobService $job_service)
    {
        $this->job_service = $job_service;
    }

    public function index (Request $request)
    {
        $jobs = $this->job_service->list(auth()->user()->id, auth()->user()->role);
        return JobResource::collection($jobs);
    }

    public function store (JobStoreRequest $request)
    {
        $input = $request->validated();
        $job = $this->job_service->create($input['title'], $input['description'], $input['user_id'], $input['is_complete']);
        return new JobResource($job);
    }
}
