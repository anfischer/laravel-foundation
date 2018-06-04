<?php

namespace Anfischer\Foundation\Tests\Stubs;

use Anfischer\Foundation\Feature\Feature;

class FeatureWithAJob extends Feature
{
    private $job;

    public function __construct($job)
    {
        $this->job = $job;
    }

    public function handle()
    {
        return $this->run($this->job);
    }
}
