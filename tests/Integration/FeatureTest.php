<?php

namespace Anfischer\Foundation\Tests\Integration;

use Anfischer\Foundation\Http\Controller;
use Anfischer\Foundation\Tests\Stubs\DummyJobWhichIsQueuable;
use Anfischer\Foundation\Tests\Stubs\DummyJobWithDefaultValues;
use Anfischer\Foundation\Tests\Stubs\FeatureWithAJob;
use Anfischer\Foundation\Tests\Stubs\FeatureWithAQueuableJob;
use Illuminate\Support\Facades\Queue;
use Mockery;
use Orchestra\Testbench\TestCase;

class FeatureTest extends TestCase
{
    /** @test */
    public function it_serves_a_feature_with_a_job()
    {
        $job = Mockery::mock(DummyJobWithDefaultValues::class)->makePartial();
        $job->shouldReceive('handle')->once()->andReturn('jobHandled');

        $controller = new class($job) extends Controller
        {
            private $job;

            public function __construct($job)
            {
                $this->job = $job;
            }

            public function index()
            {
                return $this->serve(FeatureWithAJob::class, [
                    'job' => $this->job,
                ]);
            }
        };

        $result = $controller->index();
        $this->assertEquals('jobHandled', $result);
    }

    /** @test */
    public function the_feature_can_be_served_from_an_instantiated_class()
    {
        $job = Mockery::mock(DummyJobWithDefaultValues::class)->makePartial();
        $job->shouldReceive('handle')->once()->andReturn('jobHandled');

        $controller = new class($job) extends Controller {
            private $job;

            public function __construct($job)
            {
                $this->job = $job;
            }

            public function index()
            {
                return $this->serve(new FeatureWithAJob($this->job));
            }
        };

        $result = $controller->index();
        $this->assertEquals('jobHandled', $result);
    }

    /** @test */
    public function it_serves_a_feature_with_a_queable_job()
    {
        Queue::fake();

        $job = DummyJobWhichIsQueuable::class;

        $controller = new class($job) extends Controller
        {
            private $job;

            public function __construct($job)
            {
                $this->job = $job;
            }

            public function index()
            {
                return $this->serve(FeatureWithAQueuableJob::class, [
                    'job' => $this->job,
                ]);
            }
        };

        $controller->index();

        Queue::assertPushed(DummyJobWhichIsQueuable::class);
    }

    /** @test */
    public function the_queue_name_can_be_specified()
    {
        Queue::fake();

        $job = DummyJobWhichIsQueuable::class;
        $feature = Mockery::mock(FeatureWithAQueuableJob::class, [$job, 'some-non-default-test-queue'])->makePartial();

        $controller = new class($feature, $job) extends Controller
        {
            private $feature;

            public function __construct($feature)
            {
                $this->feature = $feature;
            }

            public function index()
            {
                return $this->serve($this->feature);
            }
        };

        $controller->index();

        Queue::assertPushedOn('some-non-default-test-queue', DummyJobWhichIsQueuable::class);
    }
}
