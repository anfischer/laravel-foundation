<?php

namespace Anfischer\Foundation\Feature;

use Anfischer\Foundation\Job\Concerns\DispatchesJobs;
use Anfischer\Foundation\Job\Concerns\MarshalsJobs;
use Illuminate\Foundation\Bus\DispatchesJobs as BaseDispatcher;

abstract class Feature
{
    use MarshalsJobs;
    use BaseDispatcher;
    use DispatchesJobs;
}
