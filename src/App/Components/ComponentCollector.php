<?php 

namespace Kenjiefx\ScratchPHP\App\Components;

use Kenjiefx\ScratchPHP\App\Collectibles\CollectibleModel;
use Kenjiefx\ScratchPHP\App\Collectibles\CollectibleRegistry;
use Kenjiefx\ScratchPHP\App\Files\FileFactory;
use Kenjiefx\ScratchPHP\App\Themes\ThemeModel;

class ComponentCollector {

    public function __construct(
        public readonly ComponentService $componentService,
        public readonly FileFactory $fileFactory
    ) {}

    public function createCSSCollectibleRegistry (
        ComponentRegistry $componentRegistry,
        ThemeModel $themeModel
    ): CollectibleRegistry {
        $collectibleRegistry = new CollectibleRegistry();
        foreach ($componentRegistry->get() as $componentModel) {
            $collectibleModel = $this->createCSSCollectible(
                $componentModel, $themeModel
            );
            $collectibleRegistry->addCollectible($collectibleModel);
        }
        return $collectibleRegistry;
    }

    public function createCSSCollectible(
        ComponentModel $componentModel,
        ThemeModel $themeModel
    ): CollectibleModel {
        $cssFile = $this->componentService->getCssPath(
            $componentModel, $themeModel
        );
        return new CollectibleModel($cssFile);
    }

    public function createJSCollectibleRegistry (
        ComponentRegistry $componentRegistry,
        ThemeModel $themeModel
    ): CollectibleRegistry {
        $collectibleRegistry = new CollectibleRegistry();
        foreach ($componentRegistry->get() as $componentModel) {
            $collectibleModel = $this->createJSCollectible(
                $componentModel, $themeModel
            );
            $collectibleRegistry->addCollectible($collectibleModel);
        }
        return $collectibleRegistry;
    }

    public function createJSCollectible(
        ComponentModel $componentModel,
        ThemeModel $themeModel
    ): CollectibleModel {
        $cssFile = $this->componentService->getJsPath(
            $componentModel, $themeModel
        );
        return new CollectibleModel($cssFile);
    }


}