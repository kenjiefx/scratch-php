<?php 

namespace Kenjiefx\ScratchPHP\App\Implementations\VanillaPHP\PageBuilders;

use Kenjiefx\ScratchPHP\App\Events\EventBus;
use Kenjiefx\ScratchPHP\App\Events\Instances\BlockCSSCollectedEvent;
use Kenjiefx\ScratchPHP\App\Events\Instances\ComponentCSSCollectedEvent;
use Kenjiefx\ScratchPHP\App\Interfaces\ThemeServiceInterface;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;
use Symfony\Component\Filesystem\Filesystem;

class PageCSSBuilder {

    public function __construct(
        private ThemeServiceInterface $themeService,
        private Filesystem $filesystem,
        private EventBus $eventBus,
        private StaticAssetBundler $staticAssetBundler
    ) {}

    public function build(PageModel $pageModel) {
        $css = $this->staticAssetBundler->bundleCssAssets($pageModel);
        $css .= $this->buildBlockCSS($pageModel);
        $css .= $this->buildComponentCSS($pageModel);
        return $css;
    }

    public function buildComponentCSS(PageModel $pageModel): string {
        $css = "";
        $includedPaths = [];
        foreach ($pageModel->componentRegistry->getAll() as $component) {
            $componentCssPath = $this->themeService->getComponentCssPath(
                $pageModel->theme, $component
            );
            // Avoid including the same component CSS multiple times
            if (in_array($componentCssPath, $includedPaths)) {
                continue;
            }
            $includedPaths[] = $componentCssPath;
            $componentCss = "";
            if ($this->filesystem->exists($componentCssPath)) {
                $componentCss = $this->filesystem->readFile($componentCssPath);
            }
            $event = new ComponentCSSCollectedEvent(
                $pageModel, $component, $componentCss
            );
            $this->eventBus->dispatchEvent($event);
            $css .= $event->content;
        }
        return $css;
    }

    public function buildBlockCSS(PageModel $pageModel): string {
        $css = "";
        $includedPaths = [];
        foreach ($pageModel->blockRegistry->getAll() as $block) {
            $blockCssPath = $this->themeService->getBlockCssPath(
                $pageModel->theme, $block
            );
            // Avoid including the same block CSS multiple times
            if (in_array($blockCssPath, $includedPaths)) {
                continue;
            }
            $includedPaths[] = $blockCssPath;
            $blockCss = "";
            if ($this->filesystem->exists($blockCssPath)) {
                $blockCss = $this->filesystem->readFile($blockCssPath);
            }
            $event = new BlockCSSCollectedEvent(
                $pageModel, $block, $blockCss
            );
            $this->eventBus->dispatchEvent($event);
            $css .= $event->content;
        }
        return $css;
    }

}