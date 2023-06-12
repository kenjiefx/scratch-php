<?php

namespace Kenjiefx\ScratchPHP\App\Exceptions;
use Kenjiefx\ScratchPHP\App\Interfaces\ExtensionsInterface;

class MustImplementExtensionInterfaceException extends \Exception
{
    public function __construct(string $extension_namespace){
        $error  = 'Unsupported Extension! Please make sure that the extension you are using "'.$extension_namespace.'" ';
        $error .= 'is implementing the Extension Interface "'.ExtensionsInterface::class.'"';
        parent::__construct($error);
    }
}
