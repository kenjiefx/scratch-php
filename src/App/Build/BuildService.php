<?php

namespace Kenjiefx\ScratchPHP\App\Build;
use Kenjiefx\ScratchPHP\App\Configuration\AppSettings;
use Kenjiefx\ScratchPHP\App\Events\EventDispatcher;
use Kenjiefx\ScratchPHP\App\Events\OnBuildCompleteEvent;
use Kenjiefx\ScratchPHP\App\Events\OnBuildCssEvent;
use Kenjiefx\ScratchPHP\App\Events\OnBuildHtmlEvent;
use Kenjiefx\ScratchPHP\App\Events\OnBuildJsEvent;
use Kenjiefx\ScratchPHP\App\Export\ExportObject;
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
        private JSCollector $JSCollector,
        private EventDispatcher $EventDispatcher
    ){
        include __dir__.'/build.functions.php';
        AppSettings::load();
    }

    private function buildPage (PageController|null $PageController = null){

        BuildHelpers::PageController($PageController);

        $ExportableHTML = $this->buildHtml($PageController);
        $ExportableCSS = $this->buildCss($PageController);
        $ExportableJS = $this->buildJs($PageController);

        (new ExportService($ExportableHTML))->html();
        (new ExportService($ExportableCSS))->css();
        (new ExportService($ExportableJS))->js();

    }

    private function buildHtml(PageController $PageController): ExportObject{
        $Exportable = new ExportObject($PageController);
        $EventDTO = new BuildEventDTO($PageController);
        ob_start();
        include $this->ThemeController->getIndexFilePath();
        $EventDTO->content = ob_get_contents();
        ob_end_clean();
        $this->EventDispatcher->dispatchEvent(
            OnBuildHtmlEvent::class,
            $EventDTO
        );
        $Exportable->set($EventDTO->content);
        return $Exportable;
    }

    private function buildCss(PageController $PageController): ExportObject {
        $Exportable = new ExportObject($PageController);
        $EventDTO = new BuildEventDTO($PageController);
        $EventDTO->content = $this->CSSCollector->collect(
            BuildHelpers::PageController()->template()
        );
        $this->EventDispatcher->dispatchEvent(
            OnBuildCssEvent::class,
            $EventDTO
        );
        $Exportable->set($EventDTO->content);
        return $Exportable;
    }

    public function buildJs(PageController $PageController): ExportObject {
        $Exportable = new ExportObject($PageController);
        $EventDTO = new BuildEventDTO($PageController);
        $EventDTO->content = $this->JSCollector->collect(
            BuildHelpers::PageController()->template()
        );
        $this->EventDispatcher->dispatchEvent(
            OnBuildJsEvent::class,
            $EventDTO
        );
        $Exportable->set($EventDTO->content);
        return $Exportable;
    }

    /**
     * Starts the build process! 
     * @param array $options
     * @return void
     */
    public function start(array $options){
        $this->ThemeController->mount(
            AppSettings::getThemeName()
        );
        if ($options['pagePath'] !== null) {
            $this->PageRegistry->register(
                ROOT . PageRegistry::PAGES_DIR . '/' . $options['pagePath']
            );
        } else {
            /** 
             * By not providing a specific page, the build step will
             * include cleaning up the entire export directory contents.
             */
            ExportService::clearExportDir(ExportService::getdir());
            $this->PageRegistry->discover();
        }
        foreach ($this->PageRegistry->get() as $key => $PageController) {
            $PageController->PageModel->data->set('buildMode',$options['buildMode']);
            $this->buildPage(
                $PageController
            );
        }
    }


    public function completeBuild(){
        $EventDispatcher = new EventDispatcher;
        $EventDispatcher->dispatchEvent(OnBuildCompleteEvent::class,ROOT.AppSettings::getExportDirPath());
    }

}
