<?php 

namespace Kenjiefx\ScratchPHP\App\Blocks;

use Kenjiefx\ScratchPHP\App\Collectibles\CollectibleModel;
use Kenjiefx\ScratchPHP\App\Collectibles\CollectibleRegistry;
use Kenjiefx\ScratchPHP\App\Blocks\BlockRegistry;
use Kenjiefx\ScratchPHP\App\Files\FileFactory;
use Kenjiefx\ScratchPHP\App\Themes\ThemeModel;

class BlockCollector {

    public function __construct(
        public readonly BlockService $blockService,
        public readonly FileFactory $fileFactory
    ) {}

    public function createCSSCollectibleRegistry (
        BlockRegistry $blockRegistry,
        ThemeModel $themeModel
    ): CollectibleRegistry {
        $collectibleRegistry = new CollectibleRegistry();
        foreach ($blockRegistry->get() as $blockModel) {
            $collectibleModel = $this->createCSSCollectible(
                $blockModel, $themeModel
            );
            $collectibleRegistry->addCollectible($collectibleModel);
        }
        return $collectibleRegistry;
    }

    public function createCSSCollectible(
        BlockModel $blockModel,
        ThemeModel $themeModel
    ): CollectibleModel {
        $cssFile = $this->blockService->getCssPath(
            $blockModel, $themeModel
        );
        return new CollectibleModel($cssFile);
    }

    public function createJSCollectibleRegistry (
        BlockRegistry $blockRegistry,
        ThemeModel $themeModel
    ): CollectibleRegistry {
        $collectibleRegistry = new CollectibleRegistry();
        foreach ($blockRegistry->get() as $blockModel) {
            $collectibleModel = $this->createJSCollectible(
                $blockModel, $themeModel
            );
            $collectibleRegistry->addCollectible($collectibleModel);
        }
        return $collectibleRegistry;
    }

    public function createJSCollectible(
        BlockModel $blockModel,
        ThemeModel $themeModel
    ): CollectibleModel {
        $cssFile = $this->blockService->getJsPath(
            $blockModel, $themeModel
        );
        return new CollectibleModel($cssFile);
    }


}