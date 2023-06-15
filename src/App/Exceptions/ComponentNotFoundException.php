<?php

namespace Kenjiefx\ScratchPHP\App\Exceptions;

class ComponentNotFoundException extends \Exception
{
    public function __construct(string $component_name, string $component_path){
        $error  = 'Component Not Found! Attempt to use component named "'.$component_name.'" ';
        $error .= 'when it is not found in the theme in this path: '.$component_path;
        parent::__construct($error);
    }
}
