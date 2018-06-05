<?php

namespace Anfischer\Foundation\Tests\Unit;

use Anfischer\Foundation\Domains\Http\Jobs\RespondWithViewJob;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Mockery;
use Orchestra\Testbench\TestCase;

class RespondWithViewJobTest extends TestCase
{
    /** @test */
    public function it_can_respond_with_a_view_job()
    {
        $argumentsIn = [
            'test.template',
        ];

        $argumentsForFactory = [
            'test.template',
            [],
            200,
            [],
        ];

        $factory = Mockery::mock(ResponseFactory::class);
        $factory->shouldReceive('view')->once()->withArgs($argumentsForFactory)
            ->andReturn(Mockery::mock(Response::class))->getMock();

        $viewJob = new RespondWithViewJob(... $argumentsIn);
        $viewJob->handle($factory);
    }

    /** @test */
    public function the_job_can_take_parameters()
    {
        $argumentsIn = [
            'test.template',
            ['test' => 'data'],
            200,
            ['TEST_HEADER' => 'test'],
        ];

        $factory = Mockery::mock(ResponseFactory::class);
        $factory->shouldReceive('view')->once()->withArgs($argumentsIn)
            ->andReturn(Mockery::mock(Response::class))->getMock();

        $viewJob = new RespondWithViewJob(... $argumentsIn);
        $viewJob->handle($factory);
    }
}
