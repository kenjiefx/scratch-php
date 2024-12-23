<?php

namespace Kenjiefx\ScratchPHP\App\Events;
use Kenjiefx\ScratchPHP\App\Interfaces\EventInterface;
use Kenjiefx\ScratchPHP\App\Interfaces\ExtensionsInterface;

class EventDispatcher
{
    private static array $Events = [];

    private static array $ExtensionObjects = [];

    public function registerEvent(string $EventName, string $ExtensionNamespace, ExtensionsInterface $ExtensionObject, \ReflectionMethod $ReflectionMethod){
        static::$ExtensionObjects[$ExtensionNamespace]   = $ExtensionObject;
        static::$Events[$EventName][$ExtensionNamespace] = $ReflectionMethod;
    }

    public function dispatchEvent(string $EventName, mixed $data){
        if (!isset(static::$Events[$EventName])) return null;
        foreach (static::$Events[$EventName] as $ExtensionNamespace => $ReflectionMethod) {
            $ReflectionMethod->invoke(static::$ExtensionObjects[$ExtensionNamespace], $data);
        }
    }
}
