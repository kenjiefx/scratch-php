<?php

namespace Kenjiefx\ScratchPHP\App\Exceptions;
use Kenjiefx\ScratchPHP\App\Interfaces\EventInterface;

class MustImplementEventInterfaceException extends \Exception
{
    public function __construct(string $namespace){
        $error  = 'The Event that you want us to listen to "'.$namespace.'" must ';
        $error .= 'implement EventInterface "'.EventInterface::class.'"';
        parent::__construct($error);
    }
}
