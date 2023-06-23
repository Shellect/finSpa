<?php

namespace engine;

use app\models\User;
use Error;
use ErrorException;
use ReflectionException;

class App
{

    public static Container $container;
    public static array $config;
    public static User $user;

    public function __construct(array $config)
    {
        self::$container = new Container();
        self::$config = $config;
    }

    public function start(): void
    {
        $request = new Request();
        $response = Router::start($request);
        $response->send();
    }

    /**
     * @throws ReflectionException
     * @throws ErrorException
     */
    public static function createObject($type, array $params = [])
    {
        if (is_string($type)) {
            return static::$container->get($type, $params);
        }
        throw new Error('Unsupported configuration type: ' . gettype($type));
    }
}