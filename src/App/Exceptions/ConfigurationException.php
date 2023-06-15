<?php

namespace Kenjiefx\ScratchPHP\App\Exceptions;

class ConfigurationException extends \Exception
{
    public function __construct(){
        $error  = 'Strawberry configuration not found! Please make sure that the file ';
        $error .= 'strawberry.config.json exists in your root directory and is valid. ';
        $error .= 'To generate the configuration file, use init conmmand.';
        parent::__construct($error);
    }
}
