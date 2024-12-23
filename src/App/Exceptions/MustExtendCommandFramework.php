<?php

namespace Kenjiefx\ScratchPHP\App\Exceptions;
use Symfony\Component\Console\Command\Command;

class MustExtendCommandFramework extends \Exception
{
    public function __construct(string $namespace){
        $error  = 'Command ' . $namespace . ' must extend '.Command::class;
        parent::__construct($error);
    }
}
