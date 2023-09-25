<?php

namespace Kenjiefx\ScratchPHP\App\Configuration;
use Kenjiefx\ScratchPHP\App\Events\EventDispatcher;
use Kenjiefx\ScratchPHP\App\Events\OnSettingsRegistryEvent;
use Kenjiefx\ScratchPHP\App\Exceptions\MustImplementExtensionInterfaceException;
use Kenjiefx\ScratchPHP\App\Factory\ContainerFactory;
use Kenjiefx\ScratchPHP\App\Extensions\ExtensionsRegistry;
use Kenjiefx\ScratchPHP\App\Interfaces\ExtensionsInterface;

class ExtensionConfiguration
{

    private static ExtensionsRegistry $ExtensionsRegistry;

    public static function mountExtensions(array $Extensions){

        static::$ExtensionsRegistry = new ExtensionsRegistry();
        $EventDispatcher            = new EventDispatcher();

        foreach ($Extensions as $extensionNamespace => $extensionSettings) {
            
            $ExtensionObject  = ContainerFactory::create()->get($extensionNamespace);
            $ReflectionObject = new \ReflectionClass($extensionNamespace);

            if (!$ReflectionObject->implementsInterface(ExtensionsInterface::class)) {
                throw new MustImplementExtensionInterfaceException($extensionNamespace);
            }
            
            foreach ($ReflectionObject->getMethods() as $ReflectionMethod) {
                foreach ($ReflectionMethod->getAttributes() as $ReflectionAttribute) {

                    $Attribute = $ReflectionAttribute->newInstance();

                    if ($Attribute->getEvent()->getName()===OnSettingsRegistryEvent::class) {
                        $ReflectionMethod->invoke($ExtensionObject,$extensionSettings);
                        continue;
                    }

                    $EventDispatcher->registerEvent(
                        EventName: $Attribute->getEvent()->getName(),
                        ExtensionNamespace: $extensionNamespace,
                        ExtensionObject: $ExtensionObject,
                        ReflectionMethod: $ReflectionMethod
                    );

                }
            }



            static::$ExtensionsRegistry->registerExtension($ExtensionObject);
        }
    }

}
