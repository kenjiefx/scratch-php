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
            $originalContent = $this->fileService->readFile($collectible->file);
    
            $eventObject = new $eventClass($componentModel, $componentDir, $originalContent);
            $this->eventDispatcher->dispatchEvent($eventObject);
    
            $content .= $eventObject->getContent();
        }
    
        return $content;
    }
    
    


}