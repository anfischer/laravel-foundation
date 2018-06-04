<?php

namespace Anfischer\Foundation\Operation;

use Anfischer\Foundation\Job\Concerns\DispatchesJobs;
use Anfischer\Foundation\Job\Concerns\MarshalsJobs;
use Illuminate\Foundation\Bus\DispatchesJobs as BaseDispatcher;

/**
 * An abstract Operation to be extended by every self handling operation.
 */
abstract class Operation
{
    use MarshalsJobs;
    use BaseDispatcher;
    use DispatchesJobs;
}
