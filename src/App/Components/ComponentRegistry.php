<?php 

namespace Kenjiefx\ScratchPHP\App\Components;

/**
 * A registry of components.
 */
class ComponentRegistry {

    /**
     * @var ComponentModel[]
     */
    private array $components = [];

    public function __construct(

    ) {}

    /**
     * Registers a component in the registry.
     *
     * @param ComponentModel $component The component to register.
     */
    public function register(ComponentModel $component): void {
        $this->components[] = $component;
    }

    /**
     * Returns an iterator for all registered components.
     *
     * @return ComponentIterator
     */
    public function getAll(): ComponentIterator {
        return new ComponentIterator($this->components);
    }

}