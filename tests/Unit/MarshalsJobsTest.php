<?php

namespace Anfischer\Foundation\Tests\Unit;

use Anfischer\Foundation\Job\Concerns\MarshalsJobs;
use Anfischer\Foundation\Job\Job;
use Anfischer\Foundation\Tests\Stubs\DummyJob;
use Anfischer\Foundation\Tests\Stubs\DummyJobWithDefaultValues;
use Anfischer\Foundation\Tests\Stubs\DummyJobWithNoArguments;
use Anfischer\Foundation\Tests\Stubs\DummyJobWithNoConstructor;
use Anfischer\Foundation\Tests\Stubs\DummyJobWithTypeHints;
use Anfischer\Foundation\Tests\Stubs\TypeHintedClass;
use Anfischer\Foundation\Tests\Stubs\TypeHintedClassWithArrayableInterface;
use Illuminate\Database\Eloquent\Model;
use Orchestra\Testbench\TestCase;
use RuntimeException;

class MarshalsJobsTest extends TestCase
{
    private $marshal;

    public function setUp()
    {
        parent::setUp();

        $this->marshal = new class() {
            use MarshalsJobs;

            public function marshalWrapper($command, $arguments = null, $extra = [])
            {
                return $this->marshal($command, $arguments, $extra);
            }
        };
    }

    /** @test */
    public function it_marshals_a_job_by_creating_and_returning_a_new_class_instance_from_given_arguments()
    {
        $dummyJob = $this->marshal->marshalWrapper(DummyJob::class, collect([
            'foo' => 'bar',
            'bar' => 'baz',
            'baz' => 'qux',
        ]));

        $this->assertInstanceOf(DummyJob::class, $dummyJob);
        $this->assertEquals('bar', $dummyJob->foo);
        $this->assertEquals('baz', $dummyJob->bar);
        $this->assertEquals('qux', $dummyJob->baz);
    }

    /** @test */
    public function the_job_can_be_marshalled_from_an_existing_class_instance()
    {
        $dummyJob = $this->marshal->marshalWrapper(new DummyJob('bar', 'baz', 'qux'));

        $this->assertInstanceOf(DummyJob::class, $dummyJob);
        $this->assertEquals('bar', $dummyJob->foo);
        $this->assertEquals('baz', $dummyJob->bar);
        $this->assertEquals('qux', $dummyJob->baz);
    }

    /** @test */
    public function the_marshaller_can_take_extra_arguments_in_array_form()
    {
        $dummyJob = $this->marshal->marshalWrapper(
            DummyJob::class,
            collect([
                'foo' => 'bar',
                'bar' => 'baz',
                'baz' => 'qux',
            ]),
            [
                'test' => 'value',
                'anotherTest' => 'andItsValue',
            ]
        );

        $this->assertInstanceOf(DummyJob::class, $dummyJob);
        $this->assertEquals('bar', $dummyJob->foo);
        $this->assertEquals('baz', $dummyJob->bar);
        $this->assertEquals('qux', $dummyJob->baz);
        $this->assertEquals('value', $dummyJob->test);
        $this->assertEquals('andItsValue', $dummyJob->anotherTest);
    }

    /** @test */
    public function if_params_has_default_value_and_no_value_is_given_the_class_is_instanciated_using_these()
    {
        $dummyJob = $this->marshal->marshalWrapper(
            DummyJobWithDefaultValues::class
        );

        $this->assertInstanceOf(DummyJobWithDefaultValues::class, $dummyJob);
        $this->assertEquals('bar', $dummyJob->foo);
        $this->assertEquals('qux', $dummyJob->baz);
    }

    /** @test */
    public function if_the_job_has_no_arguments_the_constructor_does_not_receive_any()
    {
        $dummyJob = $this->marshal->marshalWrapper(DummyJobWithNoArguments::class, collect([]));
        $this->assertInstanceOf(DummyJobWithNoArguments::class, $dummyJob);

        $dummyJob = $this->marshal->marshalWrapper(DummyJobWithNoArguments::class);
        $this->assertInstanceOf(DummyJobWithNoArguments::class, $dummyJob);
    }

    /** @test */
    public function the_marshaller_can_automatically_inject_depencies_if_they_are_type_hinted_in_the_constructor()
    {
        $dummyJob = $this->marshal->marshalWrapper(DummyJobWithTypeHints::class);

        $this->assertInstanceOf(DummyJobWithTypeHints::class, $dummyJob);
        $this->assertInstanceOf(TypeHintedClass::class, $dummyJob->typeHintedProperty);
        $this->assertInstanceOf(TypeHintedClassWithArrayableInterface::class, $dummyJob->typeHintedArrayableProperty);
    }

    /** @test */
    public function an_exception_is_thrown_if_parameters_which_can_not_be_reflected_correctly_are_present()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to map parameter [foo] to command [Anfischer\Foundation\Tests\Stubs\DummyJob]');
        $this->marshal->marshalWrapper(DummyJob::class, collect(['nonExistingProperty' => 'withSomeValue']));
    }

    /** @test */
    public function if_the_job_has_no_constructor_the_job_will_be_instantiated_anyhow()
    {
        $dummyJob = $this->marshal->marshalWrapper(DummyJobWithNoConstructor::class);
        $this->assertInstanceOf(DummyJobWithNoConstructor::class, $dummyJob);
    }

    /** @test */
    public function if_the_class_to_marshal_does_not_exist_an_exception_is_thrown()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to reflect on class NonExistingJob. Are you sure it exists and is available for autoload?');
        $this->marshal->marshalWrapper('NonExistingJob', collect(['nonExistingProperty' => 'withSomeValue']));
    }
}

class WhatEver extends Model
{
}

class Gg extends Job
{
    public $typeHintedProperty;

    public function __construct(WhatEver $typeHintedProperty)
    {
        $this->typeHintedProperty = $typeHintedProperty;
    }
}
