<?php

namespace Anfischer\Foundation\Tests\Stubs;

use Anfischer\Foundation\Feature\Feature;

class FeatureWithAQueuableJob extends Feature
{
    private $job;
    private $queue;

    public function __construct($job, $queue = 'default')
    {
        $this->job = $job;
        $this->queue = $queue;
    }

    public function handle()
    {
        return $this->runInQueue($this->job, [], [], $this->queue);
    }
}
