<?php

namespace Anfischer\Foundation\Job\Concerns;

use ArrayAccess;
use Illuminate\Container\Container;
use Illuminate\Support\Collection;
use Illuminate\Support\Optional;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;
use RuntimeException;

trait MarshalsJobs
{
    /**
     * Marshal a command from the given array accessible object.
     *
     * @param string|object $command
     * @param ArrayAccess|null $source
     * @param array $extras
     *
     * @return object
     *
     * @throws RuntimeException
     */
    protected function marshal($command, ArrayAccess $source = null, array $extras = [])
    {
        $params = (new Collection($source))->merge($extras);

        if (\is_object($command)) {
            return $command;
        }

        try {
            $reflection = new ReflectionClass($command);
            $constructorParameters = (new Optional($reflection->getConstructor()))->getParameters();

            $injected = array_map(function ($parameter) use ($command, $params) {
                return $this->mapParameterValueForCommand($command, $params, $parameter);
            }, $constructorParameters ?? []);

            return $reflection->newInstanceArgs($injected);
        } catch (ReflectionException $e) {
            throw new RuntimeException(
                "Unable to reflect on class {$command}. Are you sure it exists and is available for autoload?"
            );
        }
    }

    /**
     * Maps a source/extra value to the commands constructor parameter.
     *
     * @param $command
     * @param ArrayAccess $source
     * @param ReflectionParameter $parameter
     *
     * @return \Illuminate\Foundation\Application|mixed
     *
     * @throws \Exception
     */
    protected function mapParameterValueForCommand($command, ArrayAccess $source, ReflectionParameter $parameter)
    {
        if (isset($source[$parameter->name])) {
            return $source[$parameter->name];
        }

        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        if ($parameter->hasType() && ! $parameter->getType()->isBuiltin()) {
            return (new Container)->getInstance()->make($parameter->getClass()->getName());
        }

        throw new RuntimeException("Unable to map parameter [{$parameter->name}] to command [{$command}]");
    }
}
