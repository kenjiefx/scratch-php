<?php

namespace Kenjiefx\ScratchPHP\App\Extensions;
use Kenjiefx\ScratchPHP\App\Interfaces\ExtensionsInterface;

class ExtensionsRegistry
{

    private array $extension_objects = [];
    
    public function __construct(){

    }

    public function add_extensions(
        ExtensionsInterface $extension_object
    ){
        array_push($this->extension_objects,$extension_object);
    }

    public function get_extensions(){
        return $this->extension_objects;
    }
}
