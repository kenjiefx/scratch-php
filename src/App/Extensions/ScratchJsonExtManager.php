<?php 

namespace Kenjiefx\ScratchPHP\App\Extensions;

use Kenjiefx\ScratchPHP\App\Configurations\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Events\EventDispatcher;
use Kenjiefx\ScratchPHP\App\Events\SettingsRegisteredEvent;
use Kenjiefx\ScratchPHP\Container;

class ScratchJsonExtManager {

    private static bool $hasMounted = false;

    public function __construct(
        public readonly ConfigurationInterface $configurationInterface,
        public readonly EventDispatcher $eventDispatcher
    ) {
        
    }

    /**
     * You can only load the extensions from the configuration once.
     * @return void
     */
    public function load(){
        if (static::$hasMounted) {
            return;
        }
        $extensions = $this->configurationInterface->getExtensions();
        $this->mount($extensions);
        static::$hasMounted = true;
    }

    public function mount(array $extensions){
        foreach ($extensions as $extensionNamespace => $extensionSettings) {
            $extensionObj = Container::get()->get($extensionNamespace);
            $reflectionObject = new \ReflectionClass($extensionNamespace);
            if (!$reflectionObject->implementsInterface(ExtensionsInterface::class)) {
                throw new \Exception("Extension must implement ExtensionsInterface: $extensionNamespace");
            }
            // Invoke the constructor of the extension object
            foreach ($reflectionObject->getAttributes() as $ReflectionAttribute){
                $attribute = $ReflectionAttribute->newInstance();
            }
            foreach ($reflectionObject->getMethods() as $reflectionMethod) {
                foreach ($reflectionMethod->getAttributes() as $reflectionAttribute) {
                    $attribute = $reflectionAttribute->newInstance();
                    if ($attribute->getEvent()->getName() === SettingsRegisteredEvent::class) {
                        $reflectionMethod->invoke($extensionObj, $extensionSettings);
                        continue;
                    }
                    $this->eventDispatcher->registerEvent(
                        EventName: $attribute->getEvent()->getName(),
                        ExtensionNamespace: $extensionNamespace,
                        ExtensionObject: $extensionObj,
                        ReflectionMethod: $reflectionMethod
                    );
                }
            }
        }
    }

}