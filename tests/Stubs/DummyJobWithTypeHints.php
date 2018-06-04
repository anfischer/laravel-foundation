<?php

namespace Anfischer\Foundation\Tests\Stubs;

use Anfischer\Foundation\Job\Job;

class DummyJobWithTypeHints extends Job
{
    public $typeHintedProperty;

    public function __construct(TypeHintedClass $typeHintedProperty)
    {
        $this->typeHintedProperty = $typeHintedProperty;
    }
}
