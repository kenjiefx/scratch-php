<?php 

namespace Kenjiefx\ScratchPHP\App\Implementations\VanillaPHP\PageBuilders;

use Kenjiefx\ScratchPHP\App\Events\EventBus;
use Kenjiefx\ScratchPHP\App\Events\Instances\BlockJSCollectedEvent;
use Kenjiefx\ScratchPHP\App\Events\Instances\ComponentJsCollectedEvent;
use Kenjiefx\ScratchPHP\App\Interfaces\ThemeServiceInterface;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;
use Symfony\Component\Filesystem\Filesystem;

class PageJavaScriptBuilder {

    public function __construct(
        private ThemeServiceInterface $themeService,
        private Filesystem $filesystem,
        private EventBus $eventBus
    ) {}

    public function build(PageModel $pageModel) {
        $js = "";
        $js .= $this->buildBlockJS($pageModel);
        $js .= $this->buildComponentJS($pageModel);
        return $js;
    }

    public function buildComponentJS(PageModel $pageModel): string {
        $js = "";
        foreach ($pageModel->componentRegistry->getAll() as $component) {
            $componentJsPath = $this->themeService->getComponentJsPath(
                $pageModel->theme, $component
            );
            $componentJs = "";
            if ($this->filesystem->exists($componentJsPath)) {
                $componentJs = $this->filesystem->readFile($componentJsPath);
            }
            $event = new ComponentJsCollectedEvent(
                $pageModel, $component, $componentJs
            );
            $this->eventBus->dispatchEvent($event);
            $js .= $event->content;
        }
        return $js;
    }

    public function buildBlockJS(PageModel $pageModel): string {
        $js = "";
        foreach ($pageModel->blockRegistry->getAll() as $block) {
            $blockJsPath = $this->themeService->getBlockJsPath(
                $pageModel->theme, $block
            );
            $blockJs = "";
            if ($this->filesystem->exists($blockJsPath)) {
                $blockJs = $this->filesystem->readFile($blockJsPath);
            }
            $event = new BlockJSCollectedEvent(
                $pageModel, $block, $blockJs
            );
            $this->eventBus->dispatchEvent($event);
            $js .= $event->content;
        }
        return $js;
    }

}