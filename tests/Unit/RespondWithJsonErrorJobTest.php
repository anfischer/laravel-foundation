<?php

namespace Anfischer\Foundation\Tests\Unit;

use Anfischer\Foundation\Domains\Http\Jobs\RespondWithJsonErrorJob;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Mockery;
use Orchestra\Testbench\TestCase;

class RespondWithJsonErrorJobTest extends TestCase
{
    /** @test */
    public function it_can_respond_with_a_json_error_job()
    {
        $argumentsForFactory = [
            [
                'status' => 400,
                'error' => [
                    'code' => 400,
                    'message' => 'An error occurred',
                ],
            ],
            400,
            [],
            0,
        ];

        $factory = Mockery::mock(ResponseFactory::class);
        $factory->shouldReceive('json')->once()->withArgs($argumentsForFactory)
            ->andReturn(Mockery::mock(JsonResponse::class))->getMock();

        $viewJob = new RespondWithJsonErrorJob;
        $viewJob->handle($factory);
    }

    /** @test */
    public function the_job_can_take_parameters()
    {
        $argumentsIn = [
            'Test error',
            401,
            400,
            ['TEST_HEADER' => 'test'],
            15,
        ];
        
        $argumentsForFactory = [
            [
                'status' => 400,
                'error' => [
                    'code' => 401,
                    'message' => 'Test error',
                ],
            ],
            400,
            ['TEST_HEADER' => 'test'],
            15,
        ];

        $factory = Mockery::mock(ResponseFactory::class);
        $factory->shouldReceive('json')->once()->withArgs($argumentsForFactory)
            ->andReturn(Mockery::mock(JsonResponse::class))->getMock();
        
        $viewJob = new RespondWithJsonErrorJob(... $argumentsIn);
        $viewJob->handle($factory);
    }
}
