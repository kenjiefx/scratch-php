<?php 

namespace Kenjiefx\ScratchPHP\App\Builders;

use Kenjiefx\ScratchPHP\App\Components\ComponentService;
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
        public readonly ComponentService $componentService
    ) {}
    
    public function build(
        ThemeModel $themeModel,
        AssetsEnum $assetEnum,
        string $event
    ): string {
        $content = $this->assetCollector->collectAll($assetEnum, $themeModel);

        // Determines whether the content has already been collected for each file 
        // This is done to avoid duplicate processing of the same CSS or JS file
        // This is particularly useful when multiple components share the same file.
        $hasCollectedColletible = [];
    
        foreach ($this->componentRegistry->get() as $componentModel) {
            [$createCollectibleMethod, $eventClass] = match ($event) {
                ComponentCSSCollectedEvent::class => ['createCSSCollectible', ComponentCSSCollectedEvent::class],
                ComponentJSCollectedEvent::class => ['createJSCollectible', ComponentJSCollectedEvent::class],
                default => throw new \InvalidArgumentException("Unsupported event: {$event}")
            };

            $componentDir = $this->componentService->getComponentDir(
                $componentModel, 
                $themeModel
            );
    
            $collectible = $this->componentCollector->{$createCollectibleMethod}($componentModel, $themeModel);

            // Check if the file has already been collected
            $filePath = $collectible->file->path;
            if (!isset($hasCollectedColletible[$filePath])) {

                $originalContent = "";

                // Throw no error if the file does not exist, just use an empty string
                if ($this->fileService->fileExists($collectible->file)) {
                    $originalContent = $this->fileService->readFile($collectible->file);
                }

                // Dispatch the event and let the event listener modify the content if needed
                $eventObject = new $eventClass($componentModel, $componentDir, $originalContent);
                $this->eventDispatcher->dispatchEvent($eventObject);

                // Append the content to the final output
                $content .= $eventObject->getContent();

                // Mark this file as collected to avoid processing it again
                $hasCollectedColletible[$filePath] = true;
            }
        
        }
    
        return $content;
    }
    
    


}