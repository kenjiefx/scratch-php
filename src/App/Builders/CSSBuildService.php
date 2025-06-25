<?php 

namespace Kenjiefx\ScratchPHP\App\Builders;

use Kenjiefx\ScratchPHP\App\Builders\AssetCollector;
use Kenjiefx\ScratchPHP\App\Collectibles\CollectibleRegistry;
use Kenjiefx\ScratchPHP\App\Components\ComponentCollector;
use Kenjiefx\ScratchPHP\App\Components\ComponentRegistry;
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
    ) {
        return $this->collectibleBuilder->build(
            $themeModel,
            AssetsEnum::CSS,
            ComponentCSSCollectedEvent::class
        );
    }


}