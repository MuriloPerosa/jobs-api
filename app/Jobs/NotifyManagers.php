<?php

namespace App\Jobs;

use App\Models\Job;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyManagers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * The job instance.
     *
     * @var \App\Models\job
     */
    protected $model;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Job $model)
    {
        $this->model = $model;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $managers = User::where('role', 'MANAGER')->get();

        $managers->each(function($manager)
        {
            // send notification here
        });
    }
}
