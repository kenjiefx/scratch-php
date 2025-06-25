<?php 

namespace Kenjiefx\ScratchPHP\App\Components;

class ComponentFactory {

    private static int $idIncrementor = 1;

    /**
     * Creates a new ComponentModel instance.
     *
     * @param string $name The name of the component.
     * @return ComponentModel The created ComponentModel instance.
     */
    public static function create(string $name): ComponentModel {
        $componentId = strval(self::$idIncrementor++).uniqid();
        return new ComponentModel(
            $name,
            $componentId
        );
    }
}