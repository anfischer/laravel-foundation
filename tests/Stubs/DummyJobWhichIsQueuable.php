<?php

namespace Anfischer\Foundation\Tests\Stubs;

use Anfischer\Foundation\Job\QueueableJob;

class DummyJobWhichIsQueuable extends QueueableJob
{
    public function __construct()
    {
    }
}
