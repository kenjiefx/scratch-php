<?php

namespace Kenjiefx\ScratchPHP\App\Templates;

class TemplateModel
{

    /**
     * The names of the components used/declared in a specific template. 
     * You can use the name to retrieve the specific Component Model 
     * from the Component Registry
     */
    private array $componentUsage = [];

    /**
     * All template information are registered into the Template Object. 
     * However, since this application will render different pages which could 
     * be using the same template, duplicate information might get registered,
     * thus, we set the isFrozen value to TRUE after the processing of the 
     * very first page that is using this template.
     */
    private bool $isFrozen = false;

    public function __construct(
        private string $id,
        private string $name,
        private string $templatePath
    ){
        
    }

    public function getTemplatePath(){
        return $this->templatePath;
    }

    public function hasUsedComponent(
        string $componentName
    ){
        return isset($this->componentUsage[$componentName]);
    }

    public function addComponent(
        string $componentName
    ){
        if ($this->isFrozen) return;
        if (!isset($this->componentUsage[$componentName])) {
            $this->componentUsage[$componentName] = [
                'name' => $componentName,
                'usage' => 0
            ];
        }
        $this->componentUsage[$componentName]['usage']++;
    }

    public function listUsedComponents(){
        $components = [];
        foreach ($this->componentUsage as $name => $information) {
            array_push($components,$name);
        }
        return $components;
    }

    public function hasbeenFrozen(){
        return $this->isFrozen;
    }

    public function getId(){
        return $this->id;
    }

    public function freeze() {
        $this->isFrozen = true;
    }
}
