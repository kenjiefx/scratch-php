<?php 

namespace Kenjiefx\ScratchPHP\App\Implementations\VanillaPHP;

use Kenjiefx\ScratchPHP\App\Implementations\VanillaPHP\OutputBuffer\OutputBufferContext;
use Kenjiefx\ScratchPHP\App\Implementations\VanillaPHP\OutputBuffer\OutputBufferService;
use Kenjiefx\ScratchPHP\App\Implementations\VanillaPHP\PageBuilders\PageCSSBuilder;
use Kenjiefx\ScratchPHP\App\Implementations\VanillaPHP\PageBuilders\PageJavaScriptBuilder;
use Kenjiefx\ScratchPHP\App\Interfaces\BuildServiceInterface;
use Kenjiefx\ScratchPHP\App\Interfaces\ThemeServiceInterface;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;

class VanillaPHPBuilder implements BuildServiceInterface {

    public function __construct(
        private OutputBufferService $outputBufferService,
        private ThemeServiceInterface $themeService,
        private PageJavaScriptBuilder $pageJavaScriptBuilder,
        private PageCSSBuilder $pageCSSBuilder
    ) {}

    public function buildPageHtml(
        PageModel $pageModel
    ): string {
        $themeIndexPath = $this->themeService->getIndexPath($pageModel->theme);
        $apiFilePath = $this->getApiFilePath();
        $outputBufferContent = new OutputBufferContext(
            pageModel: $pageModel,
        );
        return $this->outputBufferService->capture(
            $apiFilePath, $themeIndexPath, $outputBufferContent
        );
    }

    public function buildPageJavascript(
        PageModel $pageModel
    ): string {
        return $this->pageJavaScriptBuilder->build($pageModel);
    }

    public function buildPageCSS(
        PageModel $pageModel
    ): string {
        return $this->pageCSSBuilder->build($pageModel);
    }

    public function getApiFilePath(): string {
        return __DIR__ . '/api.php';
    }

}