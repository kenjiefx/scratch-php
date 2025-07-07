<?php

namespace Kenjiefx\ScratchPHP;
use League\Container\Container as ContainerProvider;
use League\Container\ReflectionContainer;

/**
 * Container class for managing dependencies and service providers.
 */
class Container
{

    /**
     * Singleton instance of the League\Container\Container.
     * @var ContainerProvider
     */
    private static ContainerProvider $instance;

    /**
     * Use the get() method to get the instance.
     * @since 1.0.0
     */
    public static function get()
    {
        if (!isset(static::$instance)) {
            static::$instance = new ContainerProvider;
        }
        return static::$instance;
    }

}
