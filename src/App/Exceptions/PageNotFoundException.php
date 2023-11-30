<?php

namespace Kenjiefx\ScratchPHP\App\Exceptions;

class PageNotFoundException extends \Exception
{
    public function __construct(string $path){
        $error  = 'Page Not Found! The page you are trying to build is not found in this path "'.$path.'"';
        parent::__construct($error);
    }
}