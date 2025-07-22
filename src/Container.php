<?php

namespace Kenjiefx\ScratchPHP;
use Kenjiefx\ScratchPHP\Bindings;
use League\Container\Container as ContainerProvider;
use League\Container\ReflectionContainer;
use Psr\Container\ContainerInterface;

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
     * Initializes the container instance.
     * @param \League\Container\Container $container
     * @return ContainerProvider
     * @throws \Exception if the container instance is already set.
     */
    public static function create(ContainerInterface | null $containerInterface = null): ContainerProvider{
        if (isset(static::$instance)) {
            throw new \Exception("Container instance already set.");
        }
        static::$instance = new ContainerProvider();
        // If a specific container interface is provided, delegate to it.
        $containerInterface !== null ? 
            static::$instance->delegate($containerInterface) :
            static::$instance->delegate(new ReflectionContainer());
        return static::$instance;
    }

    public static function bind() {
        if (!isset(static::$instance)) {
            throw new \Exception("Container not initialized.");
        }
        // Bind the ConfigurationInterface to the container.
        return new Bindings(static::$instance);
    }

}