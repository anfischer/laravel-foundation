<?php

namespace Anfischer\Foundation\Tests\Stubs;

use Anfischer\Foundation\Job\Job;

class DummyJobWithTypeHints extends Job
{
    public $typeHintedProperty;
    public $typeHintedArrayableProperty;

    public function __construct(
        TypeHintedClass $typeHintedProperty,
        TypeHintedClassWithArrayableInterface $typeHintedArrayableProperty
    ) {
        $this->typeHintedProperty = $typeHintedProperty;
        $this->typeHintedArrayableProperty = $typeHintedArrayableProperty;
    }
}
