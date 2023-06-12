<?php

namespace Kenjiefx\ScratchPHP\App\Exceptions;

class MissingPageJsonFieldException extends \Exception
{
    public function __construct(string $missing_field, string $template_path){
        $error  = 'Invalid PageJSON! This exception was thrown because a PageJSON file ';
        $error .= 'file lacks required "'.$missing_field.'" field. Please see file: '.$template_path;
        parent::__construct($error);
    }
}
