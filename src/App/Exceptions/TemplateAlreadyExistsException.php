<?php

namespace Kenjiefx\ScratchPHP\App\Exceptions;

class TemplateAlreadyExistsException extends \Exception
{
    public function __construct(string $template_name){
        $error  = 'Template Already Exists! The template you are trying to create named "';
        $error .= $template_name.'" already exists in the theme.';
        parent::__construct($error);
    }
}
