<?php

namespace Kenjiefx\ScratchPHP\App\Extensions;
use Kenjiefx\ScratchPHP\App\Configuration\CommandsRegistry;
use Kenjiefx\ScratchPHP\App\Exceptions\MustExtendCommandFramework;
use Symfony\Component\Console\Command\Command;

#[\Attribute]
class RegisterCommand {

    public function __construct(string $CommandReferenceClass){
        $ReflectionObject = new \ReflectionObject(new $CommandReferenceClass());
        if (!$ReflectionObject->isSubclassOf(Command::class)) {
            throw new MustExtendCommandFramework($CommandReferenceClass);
        }
        CommandsRegistry::register($CommandReferenceClass);
    }

}