<?php

namespace Anfischer\Foundation\Tests\Unit;

use Anfischer\Foundation\Feature\Concerns\ServesFeatures;
use Anfischer\Foundation\Feature\Feature;
use ArrayAccess;
use Illuminate\Support\Collection;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Assert;

class ServesFeaturesTest extends TestCase
{
    /** @test */
    public function it_serves_a_feature_by_marshalling_it_as_a_job()
    {
        $servesFeatures = new class() {
            use ServesFeatures;

            public function marshal($command, ArrayAccess $source = null)
            {
                Assert::assertEquals(DummyFeature::class, $command);
                Assert::assertInstanceOf(Collection::class, $source);

                return $command;
            }

            public function dispatch($job) : void
            {
            }
        };

        $servesFeatures->serve(DummyFeature::class);
        $servesFeatures->serve(DummyFeature::class, []);
        $servesFeatures->serve(DummyFeature::class, [
            'foo' => 'bar',
            'baz' => 'qux',
        ]);
    }
}

class DummyFeature extends Feature
{
}
