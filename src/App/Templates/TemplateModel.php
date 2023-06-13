<?php

namespace Kenjiefx\ScratchPHP\App\Templates;
use Kenjiefx\ScratchPHP\App\Components\ComponentModel;
use Kenjiefx\ScratchPHP\App\Factory\ContainerFactory;

class TemplateModel
{

    /**
     * The names of the components used/declared in a specific template. 
     * You can use the name to retrieve the specific Component Model 
     * from the Component Registry
     */
    private array $components = [];

    public function __construct(
        private string $name
    ){
        
    }

    public function getName():string {
        return $this->name;
    }


    public function hasUsedComponent(string $name){
        return isset($this->components[$name]);
    }

    public function registerComponent (ComponentModel $ComponentModel){
        $componentName = $ComponentModel->getName();
        if (!isset($this->components[$componentName])) {
            $this->components[$componentName] = [
                'model' => $ComponentModel,
                'usage' => 0
            ];
        }
        $this->components[$componentName]['usage']++;
    }

    public function getComponents(){
        return $this->components;
    }

}
