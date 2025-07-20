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
     * @template RequestedType
     * @param class-string<RequestedType>|string $id
     * @return RequestedType|mixed
     */
    public static function get($id)
    {
        if (!isset(static::$instance)) {
            throw new \Exception("Container not initialized.");
        }
        /** @var RequestedType */
        return static::$instance->get($id);
    }

    /**
     * Set the container instance.
     * @param \League\Container\Container $container
     * @return ContainerProvider
     * @throws \Exception if the container instance is already set.
     */
    public static function set(ContainerProvider $container)
    {
        if (isset(static::$instance)) {
            throw new \Exception("Container instance already set.");
        }
        static::$instance = $container;
        static::$instance->delegate(new ReflectionContainer());
        return static::$instance;
    }

}