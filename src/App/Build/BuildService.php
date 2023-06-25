<?php

namespace Kenjiefx\ScratchPHP\App\Build;
use Kenjiefx\ScratchPHP\App\Configuration\AppSettings;
use Kenjiefx\ScratchPHP\App\Export\ExportService;
use Kenjiefx\ScratchPHP\App\Pages\PageController;
use Kenjiefx\ScratchPHP\App\Pages\PageRegistry;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;

/**
 * The BuildService class encapsulates various functionalities and methods necessary for the build process. 
 * It coordinates the retrieval of data from different sources, and processes them to generate static HTML pages.  
 */
class BuildService
{
    public function __construct(
        private ThemeController $ThemeController,
        private PageRegistry $PageRegistry,
        private CSSCollector $CSSCollector,
        private JSCollector $JSCollector
    ){
        include __dir__.'/build.functions.php';
        AppSettings::load();
        ExportService::clearExportDir(ExportService::getExportDirPath());
    }

    public function buildPage (PageController|null $PageController = null){

        if ($PageController===null) {
            $this->ThemeController->mount(AppSettings::getThemeName());
            $this->PageRegistry->discover();
            foreach ($this->PageRegistry->getPages() as $key => $PageController) {
                $this->buildPage($PageController);
            }
            return;
        }

        BuildHelpers::PageController($PageController);
        $PageController->setPageHtml($this->bufferOutput());
        $PageController->setPageCss($this->collectCss());
        $PageController->setPageJs($this->collectJs());
        $this->exportPage($PageController);
    }

    private function bufferOutput():string{
        ob_start();
        include $this->ThemeController->getIndexFilePath();
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    private function collectCss(){
        return $this->CSSCollector->collect(
            BuildHelpers::PageController()->getTemplate()
        );
    }

    private function collectJs(){
        return $this->JSCollector->collect(
            BuildHelpers::PageController()->getTemplate()
        );
    }

    private function exportPage(PageController $PageController){
        $ExportService = new ExportService($PageController);
        $ExportService->pageHtml();
        $ExportService->pageAssets();
    }

}
