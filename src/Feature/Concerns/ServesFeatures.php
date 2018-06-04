<?php

namespace Anfischer\Foundation\Feature\Concerns;

use Anfischer\Foundation\Job\Concerns\MarshalsJobs;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Bus\DispatchesJobs;

trait ServesFeatures
{
    use MarshalsJobs;
    use DispatchesJobs;

    /**
     * Serve the given feature with the given arguments.
     *
     * @param $feature
     * @param array $arguments
     *
     * @return mixed
     * @throws \Exception
     */
    public function serve($feature, array $arguments = [])
    {
        return $this->dispatch($this->marshal($feature, new Collection($arguments)));
    }
}
