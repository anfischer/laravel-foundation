<?php

namespace Anfischer\Foundation\Tests\Stubs;

use Illuminate\Contracts\Support\Arrayable;

class TypeHintedClassWithArrayableInterface implements Arrayable
{
    public function toArray()
    {
    }
}
