<?php

namespace Kenjiefx\ScratchPHP\App\Exceptions;

class ThemeAlreadyExistsException extends \Exception
{
    public function __construct(string $name){
        $error  = 'Theme Already Exists! The theme you are trying to create named "';
        $error .= $name.'" already exists in your theme library';
        parent::__construct($error);
    }
}
