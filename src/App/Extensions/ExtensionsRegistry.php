<?php

namespace Kenjiefx\ScratchPHP\App\Extensions;
use Kenjiefx\ScratchPHP\App\Interfaces\ExtensionsInterface;

class ExtensionsRegistry
{

    private array $ExtensionObjects = [];
    
    public function __construct(){

    }

    public function registerExtension(ExtensionsInterface $ExtensionObject){
        array_push($this->ExtensionObjects,$ExtensionObject);
    }

    public function getExtension(){
        return $this->ExtensionObjects;
    }
}
