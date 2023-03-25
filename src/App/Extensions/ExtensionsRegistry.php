<?php

namespace Kenjiefx\ScratchPHP\App\Extensions;
use Kenjiefx\ScratchPHP\App\Interfaces\ExtensionsInterface;

class ExtensionsRegistry
{

    private array $extensionObjs = [];
    
    public function __construct(){

    }

    public function addExtension(
        ExtensionsInterface $extensionObj
    ){
        array_push($this->extensionObjs,$extensionObj);
    }

    public function getExtensions(){
        return $this->extensionObjs;
    }
}
