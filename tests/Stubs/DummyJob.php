<?php

namespace Anfischer\Foundation\Tests\Stubs;

use Anfischer\Foundation\Job\Job;

class DummyJob extends Job
{
    public $foo;
    public $bar;
    public $baz;
    public $test;
    public $anotherTest;

    public function __construct($foo, $bar, $baz, $test = null, $anotherTest = null)
    {
        $this->foo = $foo;
        $this->bar = $bar;
        $this->baz = $baz;
        $this->test = $test;
        $this->anotherTest = $anotherTest;
    }
}
