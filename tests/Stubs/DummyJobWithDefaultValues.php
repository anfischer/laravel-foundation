<?php

namespace Anfischer\Foundation\Tests\Stubs;

use Anfischer\Foundation\Job\Job;

class DummyJobWithDefaultValues extends Job
{
    public $foo;
    public $baz;

    public function __construct($foo = 'bar', $baz = 'qux')
    {
        $this->foo = $foo;
        $this->baz = $baz;
    }

    public function handle()
    {
    }
}
