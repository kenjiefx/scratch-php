<?php 

namespace Kenjiefx\ScratchPHP\App\Implementations\ScratchJSON;

use Kenjiefx\ScratchPHP\App\Extensions\ExtensionIterator;
use Kenjiefx\ScratchPHP\App\Extensions\ExtensionSettings;
use Kenjiefx\ScratchPHP\App\Interfaces\ExtensionCollectorInterface;
use Kenjiefx\ScratchPHP\App\Extensions\ExtensionModel;

class ScratchJSONExtensionCollector implements ExtensionCollectorInterface {

    public function __construct(
        private ScratchJSONLoader $loader,
    ) {}

    public function collect(): ExtensionIterator {
        $configs = $this->loader->loadConfigFromJson();
        /** @var array<ExtensionModel> */
        $extensions = [];
        if (isset($configs['extensions']) && is_array($configs['extensions'])) {
            foreach ($configs['extensions'] as $fullyQualifiedName => $settings) {
                if (!is_array($settings)) {
                    $message = "Extension settings type for $fullyQualifiedName must be an array.";
                    throw new \Exception($message);
                }
                $extensionSettings = new ExtensionSettings();
                foreach ($settings as $key => $value) {
                    $extensionSettings[$key] = $value;
                }
                $extensions[] = new ExtensionModel(
                    fullyQualifiedName: $fullyQualifiedName,
                    settings: $extensionSettings
                );
            }
        }
        return new ExtensionIterator($extensions);
    }

}