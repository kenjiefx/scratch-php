<?php

namespace Kenjiefx\ScratchPHP\App\Build;
use Kenjiefx\ScratchPHP\App\Extensions\VentaCSS\VentaCSS;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;
use Kenjiefx\ScratchPHP\App\Pages\PageRegistry;
use Kenjiefx\ScratchPHP\App\Templates\TemplateRegistry;
use Kenjiefx\ScratchPHP\App\Configuration\AppSettings;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;

/**
 * The BuildController class encapsulates various functionalities and methods necessary for the build process. 
 * It coordinates the retrieval of data from different sources, and processes them to generate static HTML pages.  
 */
class BuildController
{

    /**
     * The directory where the static HTML, Javascript and CSS are exported into. 
     * To know where or how you can define the export directory, please see 
     * `AppSettings::get_export_dir_path_from_config()` method.
     */
    private string $export_dir_path = '';

    public function __construct(
        private ThemeController $ThemeController,
        private PageRegistry $PageRegistry,
        private TemplateRegistry $TemplateRegistry, 
        private CSSController $CSSController, 
        private JSController $JSController
    ){
        AppSettings::load();
        $this->export_dir_path = AppSettings::get_export_dir_path_from_config();
    }

    /**
     * Handles the procedural side of the build process. This method orchestrates
     * different stages of the building static contents.
     */
    public function build(){
        $this->ThemeController->mount_theme(AppSettings::get_theme_name_from_config());
        $this->PageRegistry->discover();
        $this->importBuildFns();

        /**
         * We collect information as to what components and snippets 
         * each of the page templates is using
         */
        foreach ($this->PageRegistry->getPages() as $pageModel) {
            $this->importGlobalVals($pageModel);
            $this->buildPageContents($pageModel);
            $this->buildPageAssets($pageModel);
            $this->refinePage($pageModel);
        }

        $this->clearExportDir($this->export_dir_path);

        /**
         * We export the pages into the provided export directory.
         * While it may appear that the loop above is just the same
         * as with the loop below, it is intended as we need to process
         * ALL the pages and assets first before exporting, thus catching
         * errors along the way, killing the build process without really 
         * exporting any changes to the export directory. 
         */
        foreach ($this->PageRegistry->getPages() as $pageModel) {
            $this->exportPageContents($pageModel);
            $this->exportPageAssets($pageModel);
        }

        $this->PageRegistry->clearBin();
        $this->postBuildServices();
    }

    private function buildPageContents(
        PageModel $pageModel
    ){
        $templateName = $pageModel->getTemplateName();
        $templateModel = $this->TemplateRegistry->register($templateName);

        ob_start();
        include $this->ThemeController->getIndexPath();
        $pageModel->setPageHTML(ob_get_contents());
        ob_end_clean();

        if (!$templateModel->hasbeenFrozen()) {
            $templateModel->freeze();
        }
    }

    public function buildPageAssets(
        PageModel $pageModel
    ){
        /** Global CSS and JavaScript */
        $pageCss = $this->CSSController->buildGlobalCSS();
        $pageJS = $this->JSController->buildGlobalJS();

        /** Component CSS and Javascript */
        $templateName = $pageModel->getTemplateName();
        $templateModel = $this->TemplateRegistry->getTemplateModel($templateName);
        $usedComponents = $templateModel->listUsedComponents();
        $pageCss .= $this->CSSController->buildComponentCSS($usedComponents);
        $pageJS .= $this->JSController->buildComponentJS($usedComponents);

        $pageModel->setPageCSS($pageCss);
        $pageModel->setPageJS($pageJS);
    }

    private function refinePage(
        PageModel $pageModel
    ){
        $templateName = $pageModel->getTemplateName();
        $templateModel = $this->TemplateRegistry->getTemplateModel($templateName);

        # Extensions
        foreach (AppSettings::extensions()->getExtensions() as $extension) {
            $processedHTML = $extension->mutatePageHTML($pageModel->getPageHTML());
            $processedCSS = $extension->mutatePageCSS($pageModel->getPageCSS());
            $processedJS = $extension->mutatePageJS($pageModel->getPageJS());
            $pageModel->setPageHTML($processedHTML);
            $pageModel->setPageCSS($processedCSS);
            $pageModel->setPageJS($processedJS);
        }
    }

    private function exportPageContents(
        PageModel $pageModel
    ){
        /** 
         * Retrieve and create export directory, if not existing
         */
        $pageDir = $this->export_dir_path.'/'.$pageModel->getDirPath().'/';
        if (!is_dir($pageDir)) mkdir($pageDir);

        $staticExtension = (AppSettings::build()->exportPageWithoutHTMLExtension() === true) ? '' : '.html';
        $exportPath = $pageDir.$pageModel->getName().$staticExtension;
        file_put_contents($exportPath,$pageModel->getPageHTML());
    }

    private function exportPageAssets(
        PageModel $pageModel
    ){
        $pageDirPath = ($pageModel->getDirPath()==='') ? '' : '/'.$pageModel->getDirPath(); 
        $assetsDir = $this->export_dir_path.'/assets'.$pageDirPath.'/';
        if (!is_dir($assetsDir)) mkdir($assetsDir);

        $assetsFileName = (AppSettings::build()->useRandomAssetsFileNames()===true) 
            ? $pageModel->getId() : $pageModel->getName();
            
        $exportPath = $assetsDir.$assetsFileName.'.css';
        file_put_contents($exportPath,$pageModel->getPageCSS());
        $exportPath = $assetsDir.$assetsFileName.'.js';
        file_put_contents($exportPath,$pageModel->getPageJS());
    }

    private function importBuildFns(){
        include __dir__.'/build.functions.php';
    }

    private function clearExportDir(
        string $dirToClear
    ){
        foreach (scandir($dirToClear) as $file) {
            if ($file==='.'||$file==='..') continue;
            $path = $dirToClear.'/'.$file;
            if (is_dir($path)) {
                $this->clearExportDir($path);
                rmdir($path);
            } else {
                unlink($path);
            }
        }
    }

    private function importGlobalVals(
        PageModel $pageModel
    ){
        $GLOBALS['__page_model'] = $pageModel;
    }

    public function postBuildServices(){
        foreach (AppSettings::extensions()->getExtensions() as $extension) {
            if (method_exists($extension,'onBuildComplete')) {
                $extension->onBuildComplete();
            }
        }
    }
    
}
