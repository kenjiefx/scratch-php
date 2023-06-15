<?php

namespace Kenjiefx\ScratchPHP\App\Events;
use Kenjiefx\ScratchPHP\App\Interfaces\EventInterface;

class EventDispatcher
{
    private static array $Events = [];

    public function registerEvent(string $EventName, string $ExtensionNamespace, \ReflectionMethod $ReflectionMethod){
        static::$Events[$EventName][$ExtensionNamespace] = $ReflectionMethod;
    }

    public function dispatchEvent(string $EventName, mixed $data){
        if (!isset(static::$Events[$EventName])) return null;
        foreach (static::$Events[$EventName] as $ExtensionNamespace => $ReflectionMethod) {
            $data = $ReflectionMethod->invoke(new $ExtensionNamespace, $data);
        }
        return $data;
    }
}
