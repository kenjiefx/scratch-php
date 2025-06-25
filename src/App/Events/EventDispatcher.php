<?php

namespace Kenjiefx\ScratchPHP\App\Events;
use Kenjiefx\ScratchPHP\App\Events\EventInterface;
use Kenjiefx\ScratchPHP\App\Extensions\ExtensionsInterface;

class EventDispatcher
{
    private static array $Events = [];

    private static array $ExtensionObjects = [];

    public function registerEvent(
        string $EventName, 
        string $ExtensionNamespace, 
        ExtensionsInterface $ExtensionObject, 
        \ReflectionMethod $ReflectionMethod
    ){
        static::$ExtensionObjects[$ExtensionNamespace]   = $ExtensionObject;
        static::$Events[$EventName][$ExtensionNamespace] = $ReflectionMethod;
    }

    public function dispatchEvent(EventInterface $event){
        $EventName = $event->getName();
        if (!isset(static::$Events[$EventName])) return null;
        foreach (static::$Events[$EventName] as $ExtensionNamespace => $ReflectionMethod) {
            $ReflectionMethod->invoke(static::$ExtensionObjects[$ExtensionNamespace], $event);
        }
    }
}
