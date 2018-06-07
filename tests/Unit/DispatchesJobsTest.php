<?php

namespace Anfischer\Foundation\Tests\Unit;

use Anfischer\Foundation\Job\Concerns\DispatchesJobs;
use Anfischer\Foundation\Job\Job;
use Anfischer\Foundation\Tests\Stubs\DummyJob;
use Anfischer\Foundation\Tests\Stubs\DummyJobWhichIsQueuable;
use Anfischer\Foundation\Tests\Stubs\DummyJobWithDefaultValues;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Mockery;
use Orchestra\Testbench\TestCase;

class DispatchesJobsTest extends TestCase
{
    /** @test */
    public function it_runs_a_job_from_a_class_string()
    {
        $arguments = [
            'foo' => 'bar'
        ];

        $jobDispatcher = Mockery::mock(JobDispatcher::class)->makePartial();
        $jobDispatcher->shouldReceive('marshal')->once()
            ->withArgs([DummyJob::class, Mockery::on(function ($closure) use ($arguments) {
                return $closure instanceof Collection && $closure->toArray() === $arguments;
            }), []])
            ->andReturn(new class extends Job {
            });
        $jobDispatcher->shouldReceive('dispatch')->once()->withArgs([Mockery::type(Job::class)]);

        $jobDispatcher->run(DummyJob::class, $arguments);
    }

    /** @test */
    public function it_runs_a_job_from_an_instantiated_class()
    {
        $arguments = [
            'foo' => 'bar',
            'baz' => 'qux',
        ];

        $jobDispatcher = Mockery::mock(JobDispatcher::class)->makePartial();
        $jobDispatcher->shouldReceive('dispatch')->once()->withArgs([Mockery::type(DummyJobWithDefaultValues::class)]);

        $jobDispatcher->run(new DummyJobWithDefaultValues, $arguments);
    }

    /** @test */
    public function it_runs_a_job_where_arguments_are_an_instance_of_illuminate_request()
    {
        $arguments = new Request;

        $jobDispatcher = Mockery::mock(JobDispatcher::class)->makePartial();
        $jobDispatcher->shouldReceive('marshal')->once()->withArgs([DummyJob::class, Mockery::type(Request::class), []])->andReturn(new class extends Job {
        });
        $jobDispatcher->shouldReceive('dispatch')->once()->withArgs([Mockery::type(Job::class)]);

        $jobDispatcher->run(DummyJob::class, $arguments);
    }

    /** @test */
    public function the_job_with_the_illuminate_request_can_take_additional_arguments()
    {
        $arguments = new Request;
        $extra = [
            'foo' => 'bar',
            'baz' => 'qux',
        ];

        $jobDispatcher = Mockery::mock(JobDispatcher::class)->makePartial();
        $jobDispatcher->shouldReceive('marshal')->once()->withArgs([DummyJob::class, Mockery::type(Request::class), $extra])->andReturn(new class extends Job {
        });
        $jobDispatcher->shouldReceive('dispatch')->once()->withArgs([Mockery::type(Job::class)]);

        $jobDispatcher->run(DummyJob::class, $arguments, $extra);
    }

    /** @test */
    public function it_can_queue_a_job_instead_of_handling_it_directly()
    {
        $job = Mockery::mock(DummyJobWhichIsQueuable::class)->makePartial();
        $job->shouldReceive('onQueue')->once()->withArgs(['default']);

        $arguments = new Request;
        $extra = [
            'foo' => 'bar',
            'baz' => 'qux',
        ];

        $jobDispatcher = Mockery::mock(JobDispatcher::class)->makePartial();
        $jobDispatcher->shouldReceive('marshal')->once()->withArgs([DummyJobWhichIsQueuable::class, Mockery::type(Request::class), $extra])->andReturn($job);
        $jobDispatcher->shouldReceive('dispatch')->once()->withArgs([$job]);

        $jobDispatcher->runInQueue($job, $arguments, $extra);
    }
}

class JobDispatcher
{
    use DispatchesJobs;

    public function marshal()
    {
    }

    public function dispatch()
    {
    }
}
