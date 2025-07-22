<?php 

namespace Kenjiefx\ScratchPHP\App\Components;

/**
 * Represents a component in the application.
 */
class ComponentModel {

    public function __construct(
        /**
         * The unique identifier for the component.
         * @var string
         */
        public readonly string $id,

        /**
         * The name of the component.
         * This typically contains the namespace and the name
         * @example "ExampleComponents/ComponentName"
         * @var string
         */
        public readonly string $name,

        /**
         * Data associated to the component.
         */
        public readonly ComponentData $data
    ) {}

}