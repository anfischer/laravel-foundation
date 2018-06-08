<?php

namespace Anfischer\Foundation\Job\Concerns;

use Anfischer\Foundation\Job\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

trait DispatchesJobs
{
    /**
     * Beautifier function to be called instead of the
     * laravel function dispatch.
     *
     * @param string|object $job
     * @param array|\Illuminate\Http\Request $arguments
     * @param array $extra
     *
     * @return mixed
     */
    public function run($job, $arguments = [], array $extra = [])
    {
        return $this->dispatch($this->prepare($job, $arguments, $extra));
    }

    /**
     * Runs the job in the given queue.
     *
     * @param string|object $job
     * @param array|\Illuminate\Http\Request $arguments
     * @param array $extra
     * @param string $queue
     *
     * @return mixed
     */
    public function runInQueue($job, $arguments = [], array $extra = [], string $queue = 'default')
    {
        $job = $this->prepare($job, $arguments, $extra);
        $job->onQueue($queue);

        return $this->dispatch($job);
    }


    /**
     * Prepare a job by marshalling it if needed.
     *
     * @param string|Job $job
     * @param array|\Illuminate\Http\Request $arguments
     * @param array $extra
     *
     * @return Job
     */
    private function prepare($job, $arguments, array $extra) : Job
    {
        if ($arguments instanceof Request) {
            return $this->marshal($job, $arguments, $extra);
        }

        if (! \is_object($job)) {
            return $this->marshal($job, new Collection($arguments), $extra);
        }

        return $job;
    }
}
