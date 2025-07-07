<?php 

namespace Kenjiefx\ScratchPHP\App\Builders;

use Kenjiefx\ScratchPHP\App\Builders\AssetCollector;
use Kenjiefx\ScratchPHP\App\Collectibles\CollectibleRegistry;
use Kenjiefx\ScratchPHP\App\Components\ComponentCollector;
use Kenjiefx\ScratchPHP\App\Components\ComponentRegistry;
use Kenjiefx\ScratchPHP\App\Events\BlockCSSCollectedEvent;
use Kenjiefx\ScratchPHP\App\Events\ComponentCSSCollectedEvent;
use Kenjiefx\ScratchPHP\App\Events\EventDispatcher;
use Kenjiefx\ScratchPHP\App\Files\FileService;
use Kenjiefx\ScratchPHP\App\Themes\ThemeModel;

class CSSBuildService {

    public function __construct(
        public readonly CollectibleBuilder $collectibleBuilder
    ) {}

    public function build(
        ThemeModel $themeModel
    ): string {
        return $this->buildGlobalAssets($themeModel) .
            $this->buildComponentAssets($themeModel) .
            $this->buildBlockAssets($themeModel);
    }

    public function buildGlobalAssets(
        ThemeModel $themeModel
    ): string {
        return $this->collectibleBuilder->buildGlobalAssets(
            $themeModel,
            AssetsEnum::CSS
        );
    }

    public function buildComponentAssets(
        ThemeModel $themeModel
    ): string {
        return $this->collectibleBuilder->buildDomainAssets(
            $themeModel,
            AssetsEnum::CSS,
            ComponentCSSCollectedEvent::class
        );
    }

    public function buildBlockAssets(
        ThemeModel $themeModel
    ): string {
        return $this->collectibleBuilder->buildDomainAssets(
            $themeModel,
            AssetsEnum::CSS,
            BlockCSSCollectedEvent::class
        );
    }


}