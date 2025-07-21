<?php 

namespace Kenjiefx\ScratchPHP\App\Orchestrators;

use Kenjiefx\ScratchPHP\App\Events\EventBus;
use Kenjiefx\ScratchPHP\App\Events\Instances\PageAfterBuildEvent;
use Kenjiefx\ScratchPHP\App\Events\Instances\PageBeforeBuildEvent;
use Kenjiefx\ScratchPHP\App\Events\Instances\PageCSSBuildCompleteEvent;
use Kenjiefx\ScratchPHP\App\Events\Instances\PageHTMLBuildCompleteEvent;
use Kenjiefx\ScratchPHP\App\Events\Instances\PageJSBuildCompleteEvent;
use Kenjiefx\ScratchPHP\App\Implementations\VanillaPHP\PageBuilders\PageJavaScriptBuilder;
use Kenjiefx\ScratchPHP\App\Interfaces\BuildServiceInterface;
use Kenjiefx\ScratchPHP\App\Interfaces\ExportServiceInterface;
use Kenjiefx\ScratchPHP\App\Pages\PageContent;
use Kenjiefx\ScratchPHP\App\Pages\PageIterator;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;

class BuildOrchestrator {

    public function __construct(
        private readonly BuildServiceInterface $buildService,
        private readonly ExportServiceInterface $exportService,
        private readonly EventBus $eventBus
    ) {}

    public function build(PageIterator $pages) {
        foreach ($pages as $page) {
            $event = new PageBeforeBuildEvent($page);
            $this->eventBus->dispatchEvent($event);
            $content = new PageContent(
                html: $this->buildPageHtml($page),
                javascript: $this->buildPageJavascript($page),
                css: $this->buildPageCSS($page)
            );
            $event = new PageAfterBuildEvent($page);
            $this->eventBus->dispatchEvent($event);
            $this->exportService->exportPageContents($content, $page);
            $this->exportService->exportStaticAssets($page->staticAssetRegistry, $page);
        }
    }

    public function buildPageHtml(PageModel $pageModel) {
        $html = $this->buildService->buildPageHtml($pageModel);
        $event = new PageHTMLBuildCompleteEvent(
            $pageModel, $html
        );
        $this->eventBus->dispatchEvent($event);
        return $event->content;
    }

    public function buildPageJavascript(PageModel $pageModel): string {
        $javascript = $this->buildService->buildPageJavascript($pageModel);
        $event = new PageJSBuildCompleteEvent(
            $pageModel, $javascript
        );
        $this->eventBus->dispatchEvent($event);
        return $event->content;
    }

    public function buildPageCSS(PageModel $pageModel): string {
        $css = $this->buildService->buildPageCSS($pageModel);
        $event = new PageCSSBuildCompleteEvent(
            $pageModel, $css
        );
        $this->eventBus->dispatchEvent($event);
        return $event->content;
    }

}