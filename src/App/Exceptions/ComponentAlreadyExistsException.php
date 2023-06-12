<?php

namespace Kenjiefx\ScratchPHP\App\Exceptions;

class ComponentAlreadyExistsException extends \Exception
{
    public function __construct(string $component_name){
        $error  = 'Component Already Exists! The component you are trying to create named "';
        $error .= $component_name.'" alrady exists in the theme.';
        parent::__construct($error);
    }
}
