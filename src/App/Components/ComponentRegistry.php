<?php

namespace Kenjiefx\ScratchPHP\App\Components;

/**
 * Registers and manages the components used within the template.
 * This object holds an array of
 */
class ComponentRegistry {

    /**
     * An array of Component Models
     */
    private static array $registry = [];

    public function __construct(

    ) {}

    public function register(ComponentModel $componentModel){
        array_push(self::$registry, $componentModel);
    }

    public function get(): ComponentsIterator {
        return new ComponentsIterator(self::$registry);
    }

    public function clear(){
        self::$registry = [];
    }

}