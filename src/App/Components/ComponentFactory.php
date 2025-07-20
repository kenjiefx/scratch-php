<?php 

namespace Kenjiefx\ScratchPHP\App\Components;

use Kenjiefx\ScratchPHP\App\Utils\UniqueIdGenerator;

class ComponentFactory {

    public function __construct(
        private UniqueIdGenerator $uniqueIdGenerator
    ) {}

    /**
     * Creates a new ComponentModel instance.
     *
     * @param string $name The name of the component.
     * @return ComponentModel The created ComponentModel instance.
     */
    public function create(string $name, array $data): ComponentModel {
        $uniqueId = $this->uniqueIdGenerator->generate();
        $componentData = new ComponentData();
        foreach ($data as $key => $value) {
            $componentData[$key] = $value;
        }
        return new ComponentModel (
            id: $uniqueId,
            name: $name,
            data: $componentData
        );
    }

}