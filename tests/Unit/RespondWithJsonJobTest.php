<?php

namespace Anfischer\Foundation\Tests\Unit;

use Anfischer\Foundation\Domains\Http\Jobs\RespondWithJsonJob;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Mockery;
use Orchestra\Testbench\TestCase;

class RespondWithJsonJobTest extends TestCase
{
    /** @test */
    public function it_can_respond_with_a_json_job()
    {
        $argumentsForFactory = [
            [
                'data' => null,
                'status' => 200,
            ],
            200,
            [],
            0,
        ];

        $factory = Mockery::mock(ResponseFactory::class);
        $factory->shouldReceive('json')->once()->withArgs($argumentsForFactory)
            ->andReturn(Mockery::mock(JsonResponse::class))->getMock();

        $viewJob = new RespondWithJsonJob;
        $viewJob->handle($factory);
    }

    /** @test */
    public function the_job_can_take_parameters()
    {
        $argumentsIn = [
            ['test' => 'data'],
            200,
            ['TEST_HEADER' => 'test'],
            15,
        ];

        $argumentsForFactory = [
            [
                'data' => ['test' => 'data'],
                'status' => 200,
            ],
            200,
            ['TEST_HEADER' => 'test'],
            15,
        ];

        $factory = Mockery::mock(ResponseFactory::class);
        $factory->shouldReceive('json')->once()->withArgs($argumentsForFactory)
            ->andReturn(Mockery::mock(JsonResponse::class))->getMock();

        $viewJob = new RespondWithJsonJob(... $argumentsIn);
        $viewJob->handle($factory);
    }
}
