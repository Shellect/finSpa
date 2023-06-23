<?php

namespace engine;

use ErrorException;
use ReflectionClass;
use ReflectionException;

class Container
{
    private array $singletons = [];

    /**
     * @throws ReflectionException
     * @throws ErrorException
     */
    public function get($class, $params = [])
    {
        if (isset($this->singletons[$class])) {
            return $this->singletons[$class];
        }
        $classReflector = new ReflectionClass($class);
        $constructReflector = $classReflector->getConstructor();
        if ($constructReflector === null) {
            return new $class;
        }
        $constructArguments = $constructReflector->getParameters();
        if (empty($constructArguments)) {
            return new $class;
        }
        $args = [];
        foreach ($constructArguments as $argument) {
            $argumentType = $argument->getType()?->getName();
            $argumentName = $argument->getName();
            if ($argument->getType()->isBuiltin()) {
                if (array_key_exists($argumentName, $params)) {
                    $args[$argumentName] = $params[$argumentName];
                } else {
                    throw new ErrorException("The is no parameter with same name");
                }
            } else {
                $args[$argument->getName()] = $this->get($argumentType);
            }
        }
        return new $class(...$args);
    }
}