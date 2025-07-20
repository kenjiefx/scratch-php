<?php 

namespace Kenjiefx\ScratchPHP\App\Implementations\VanillaPHP;

use Kenjiefx\ScratchPHP\App\Events\EventBus;
use Kenjiefx\ScratchPHP\App\Events\Instances\PageCSSBuildCompleteEvent;
use Kenjiefx\ScratchPHP\App\Events\Instances\PageHTMLBuildCompleteEvent;
use Kenjiefx\ScratchPHP\App\Events\Instances\PageJSBuildCompleteEvent;
use Kenjiefx\ScratchPHP\App\Implementations\VanillaPHP\OutputBuffer\OutputBufferContext;
use Kenjiefx\ScratchPHP\App\Implementations\VanillaPHP\OutputBuffer\OutputBufferService;
use Kenjiefx\ScratchPHP\App\Implementations\VanillaPHP\PageBuilders\PageCSSBuilder;
use Kenjiefx\ScratchPHP\App\Implementations\VanillaPHP\PageBuilders\PageJavaScriptBuilder;
use Kenjiefx\ScratchPHP\App\Interfaces\BuildServiceInterface;
use Kenjiefx\ScratchPHP\App\Interfaces\ThemeServiceInterface;
use Kenjiefx\ScratchPHP\App\Pages\PageContent;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;

class VanillaPHPBuilder implements BuildServiceInterface {

    public function __construct(
        private OutputBufferService $outputBufferService,
        private ThemeServiceInterface $themeService,
        private PageJavaScriptBuilder $pageJavaScriptBuilder,
        private PageCSSBuilder $pageCSSBuilder,
        private EventBus $eventBus
    ) {}

    public function buildPage(PageModel $pageModel): PageContent {
        $htmlContent = $this->buildPageHtml($pageModel);
        $jsContent = $this->buildPageJavascript($pageModel);
        $cssContent = $this->buildPageCSS($pageModel);
        return new PageContent(
            html: $htmlContent,
            javascript: $jsContent,
            css: $cssContent
        );
    }

    public function buildPageHtml(
        PageModel $pageModel
    ) {
        $themeIndexPath = $this->themeService->getIndexPath($pageModel->theme);
        $apiFilePath = $this->getApiFilePath();
        $outputBufferContent = new OutputBufferContext(
            pageModel: $pageModel,
        );
        $htmlContent = $this->outputBufferService->capture(
            $apiFilePath, $themeIndexPath, $outputBufferContent
        );
        $event = new PageHTMLBuildCompleteEvent(
            $pageModel, $htmlContent
        );
        $this->eventBus->dispatchEvent($event);
        return $event->content;
    }

    public function buildPageJavascript(
        PageModel $pageModel
    ) {
        $jsContent = $this->pageJavaScriptBuilder->build($pageModel);
        $event = new PageJSBuildCompleteEvent(
            $pageModel, $jsContent
        );
        $this->eventBus->dispatchEvent($event);
        return $event->content;
    }

    public function buildPageCSS(
        PageModel $pageModel
    ) {
        $cssContent = $this->pageCSSBuilder->build($pageModel);
        $event = new PageCSSBuildCompleteEvent(
            $pageModel, $cssContent
        );
        $this->eventBus->dispatchEvent($event);
        return $event->content;
    }

    public function getApiFilePath(): string {
        return __DIR__ . '/api.php';
    }

}