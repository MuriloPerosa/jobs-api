<?php

namespace App\Services;

use App\Jobs\NotifyManagers;
use App\Models\Job;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class used to handle jobs
 */
class JobService
{

    /**
     * Hanlde to create a new job
     *
     * @param string $title
     * @param string $description
     * @param integer $user_id
     * @param boolean $is_complete
     * @return Job
     */
    public function create (string $title, string $description, int $user_id,  bool $is_complete) : Job
    {
        $job = Job::create([
            'title'       => $title,
            'description' => $description,
            'user_id'     => $user_id,
            'is_complete' => $is_complete,
        ]);

        // notify managers
        NotifyManagers::dispatch($job)->onQueue('notify_managers');

        return $job;
    }

    /**
     * Handle to list jobs
     *
     * @param integer $user_id
     * @param string $role
     * @return Job
     */
    public function list (int $user_id,  string $role) : LengthAwarePaginator
    {
        return $role == 'MANAGER' ? Job::paginate(15) : Job::where('user_id', $user_id)->paginate(15);
    }
}
