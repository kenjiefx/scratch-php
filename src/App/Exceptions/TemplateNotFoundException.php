<?php

namespace Kenjiefx\ScratchPHP\App\Exceptions;

class TemplateNotFoundException extends \Exception
{
    public function __construct(string $template_name, string $template_path){
        $error  = 'Template Not Found! Attempt to use the template name "'.$template_name.'" ';
        $error .= 'when it is not found in the theme with the path: '.$template_path;
        parent::__construct($error);
    }
}
