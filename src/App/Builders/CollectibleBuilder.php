<?php 

namespace Kenjiefx\ScratchPHP\App\Builders;

use Kenjiefx\ScratchPHP\App\Blocks\BlockCollector;
use Kenjiefx\ScratchPHP\App\Blocks\BlockRegistry;
use Kenjiefx\ScratchPHP\App\Blocks\BlockService;
use Kenjiefx\ScratchPHP\App\Components\ComponentService;
use Kenjiefx\ScratchPHP\App\Events\BlockCSSCollectedEvent;
use Kenjiefx\ScratchPHP\App\Events\BlockJSCollectedEvent;
use Kenjiefx\ScratchPHP\App\Events\ComponentCSSCollectedEvent;
use Kenjiefx\ScratchPHP\App\Events\ComponentJSCollectedEvent;
use Kenjiefx\ScratchPHP\App\Events\EventDispatcher;
use Kenjiefx\ScratchPHP\App\Events\EventInterface;
use Kenjiefx\ScratchPHP\App\Files\FileService;
use Kenjiefx\ScratchPHP\App\Builders\AssetCollector;
use Kenjiefx\ScratchPHP\App\Components\ComponentCollector;
use Kenjiefx\ScratchPHP\App\Components\ComponentRegistry;
use Kenjiefx\ScratchPHP\App\Themes\ThemeModel;

/**
 * CollectibleBuilder is responsible for collecting component and block 
 * assets (CSS and JS) for a page in a theme.
 */
class CollectibleBuilder {

    public function __construct(
        public readonly FileService $fileService,
        public readonly AssetCollector $assetCollector,
        public readonly ComponentRegistry $componentRegistry,
        public readonly ComponentCollector $componentCollector,
        public readonly EventDispatcher $eventDispatcher,
        public readonly ComponentService $componentService,
        public readonly BlockRegistry $blockRegistry,
        public readonly BlockService $blockService,
        public readonly BlockCollector $blockCollector
    ) {}

    public function buildGlobalAssets(
        ThemeModel $themeModel,
        AssetsEnum $assetEnum
    ){
        return $this->assetCollector->collectAll($assetEnum, $themeModel);
    }
    
    public function buildDomainAssets(
        ThemeModel $themeModel,
        AssetsEnum $assetEnum,
        string $event
    ): string {

        $content = "";

        // Determines whether the content has already been collected for each file 
        // This is done to avoid duplicate processing of the same CSS or JS file
        // This is particularly useful when multiple components share the same file.
        $hasCollectedColletible = [];

        $registry = $this->getRegistryBasedOnEvent($event);
        $service = $this->getServiceBasedOnEvent($event);
    
        foreach ($registry->get() as $model) {
            [$createCollectibleMethod, $eventClass] = match ($event) {
                ComponentCSSCollectedEvent::class => ['createCSSCollectible', ComponentCSSCollectedEvent::class],
                ComponentJSCollectedEvent::class => ['createJSCollectible', ComponentJSCollectedEvent::class],
                BlockCSSCollectedEvent::class => ['createCSSCollectible', BlockCSSCollectedEvent::class],
                BlockJSCollectedEvent::class => ['createJSCollectible', BlockJSCollectedEvent::class],
                default => throw new \InvalidArgumentException("Unsupported event: {$event}")
            };

            if ($service === "ComponentService") {
                $theDir = $this->componentService->getComponentDir(
                    $model, $themeModel
                );
                $collectorType = $this->componentCollector;
            } elseif ($service === "BlockService") {
                $theDir = $this->blockService->getBlockDir(
                    $model, $themeModel
                );
                $collectorType = $this->blockCollector;
            } else {
                throw new \InvalidArgumentException("Unsupported service: {$service}");
            }
    
            $collectible = $collectorType->{$createCollectibleMethod}($model, $themeModel);

            // Check if the file has already been collected
            $filePath = $collectible->file->path;
            if (!isset($hasCollectedColletible[$filePath])) {

                $originalContent = "";

                // Throw no error if the file does not exist, just use an empty string
                if ($this->fileService->fileExists($collectible->file)) {
                    $originalContent = $this->fileService->readFile($collectible->file);
                }

                // Dispatch the event and let the event listener modify the content if needed
                $eventObject = new $eventClass([
                    "model" => $model,
                    "dir" => $theDir,
                    "content" => $originalContent,
                ]);
                $this->eventDispatcher->dispatchEvent($eventObject);

                // Append the content to the final output
                $content .= $eventObject->getContent();

                // Mark this file as collected to avoid processing it again
                $hasCollectedColletible[$filePath] = true;
            }
        
        }
    
        return $content;
    }

    public function getRegistryBasedOnEvent(string $event): ComponentRegistry | BlockRegistry {
        return match ($event) {
            ComponentCSSCollectedEvent::class, ComponentJSCollectedEvent::class => $this->componentRegistry,
            BlockCSSCollectedEvent::class, BlockJSCollectedEvent::class => $this->blockRegistry,
            default => throw new \InvalidArgumentException("Unsupported event: {$event}")
        };
    }

    public function getServiceBasedOnEvent(string $event): string {
        return match ($event) {
            ComponentCSSCollectedEvent::class, ComponentJSCollectedEvent::class => "ComponentService",
            BlockCSSCollectedEvent::class, BlockJSCollectedEvent::class => "BlockService",
            default => throw new \InvalidArgumentException("Unsupported event: {$event}")
        };
    }
    
    


}