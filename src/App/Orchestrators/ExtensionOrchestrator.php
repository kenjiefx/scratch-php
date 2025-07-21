<?php 

namespace Kenjiefx\ScratchPHP\App\Orchestrators;

use Kenjiefx\ScratchPHP\App\Events\EventRegistry;
use Kenjiefx\ScratchPHP\App\Events\Instances\ExtensionSettingsRegisterEvent;
use Kenjiefx\ScratchPHP\App\Events\ListensTo;
use Kenjiefx\ScratchPHP\App\Extensions\ExtensionInterface;
use Kenjiefx\ScratchPHP\App\Extensions\ExtensionModel;
use Kenjiefx\ScratchPHP\App\Interfaces\ExtensionCollectorInterface;
use Kenjiefx\ScratchPHP\Container;

class ExtensionOrchestrator {
    
    public function __construct(
        private ExtensionCollectorInterface $extensionCollector,
        private EventRegistry $eventRegistry
    ) {}

    /**
     * Mounts the extensions and registers their event listeners.
     */
    public function mount() {
        $extensions = $this->extensionCollector->collect();
        foreach ($extensions as $extension) {
            $this->register($extension);
        }
    }

    /**
     * Registers an extension
     * @param ExtensionModel $extension
     * @throws \Exception
     * @return void
     */
    public function register(ExtensionModel $extension) {

        // Get the fully qualified name of the extension and create an instance
        $fullyQualifiedName = $extension->fullyQualifiedName;

        // Retrieve the extension object from the container
        $extensionObject = Container::get($fullyQualifiedName);

        // Get the reflection object for the extension class
        $reflectionObject = new \ReflectionClass($fullyQualifiedName);

        // Ensure the extension is a valid instance of ExtensionInterface
        if (!$reflectionObject->implementsInterface(ExtensionInterface::class)) {
            throw new \Exception("Extension must implement ExtensionsInterface: $fullyQualifiedName");
        }
        foreach ($reflectionObject->getMethods() as $reflectionMethod) {

            foreach ($reflectionMethod->getAttributes() as $reflectionAttribute) {

                // Check if the attribute is a ListensTo attribute
                if ($reflectionAttribute->getName() !== ListensTo::class) {
                    continue; 
                }

                // Create an instance of the ListensTo attribute
                $attribute = $reflectionAttribute->newInstance();

                // Get the event name from the attribute
                $eventName = $attribute->getEvent();

                // If the event is ExtensionSettingsRegisterEvent, invoke the method directly
                if ($eventName === ExtensionSettingsRegisterEvent::class) {
                    $reflectionMethod->invoke($extensionObject, $extension->settings);
                    continue;
                }

                // If the event is not ExtensionSettingsRegisterEvent, register it as an event listener
                $this->eventRegistry->addEventListener(
                    $eventName,
                    function(...$args) use ($extensionObject, $reflectionMethod) {
                        return $reflectionMethod->invoke($extensionObject, ...$args);
                    }
                );
            }
        }
    }

}