<?php

namespace Kenjiefx\ScratchPHP\App\Components;

class ComponentModel
{
    public function __construct(
        private string $componentName, 
        private string $templateRefName, 
        private string $componentHtmlPath,
        private string $componentJsPath,
        private string $componentCssPath
    ){

    }

    public function getComponentHtmlPath(){
        return $this->componentHtmlPath;
    }

    public function getComponentName(){
        return $this->componentName;
    }
}
