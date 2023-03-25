<?php

namespace Kenjiefx\ScratchPHP\App\Build;
use Kenjiefx\ScratchPHP\App\Extensions\VentaCSS\VentaCSS;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;
use Kenjiefx\ScratchPHP\App\Pages\PageRegistry;
use Kenjiefx\ScratchPHP\App\Templates\TemplateRegistry;
use Kenjiefx\ScratchPHP\App\Configuration\AppSettings;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;

class BuildController
{

    private string $exportDirPath = '';

    public function __construct(
        private ThemeController $themeController,
        private PageRegistry $pageRegistry,
        private TemplateRegistry $templateRegistry, 
        private CSSController $CSSController, 
        private JSController $JSController
    ){
        AppSettings::load();
        $this->exportDirPath = ROOT.AppSettings::getExportDirPath();
    }

    public function buildSite(){
        $this->themeController->useTheme(AppSettings::getThemeName());
        $this->pageRegistry->discover();
        $this->importBuildFns();

        /**
         * We collect information as to what components and snippets 
         * each of the page templates is using
         */
        foreach ($this->pageRegistry->getPages() as $pageModel) {
            $this->importGlobalVals($pageModel);
            $this->buildPageContents($pageModel);
            $this->buildPageAssets($pageModel);
            $this->refinePage($pageModel);
        }

        $this->clearExportDir($this->exportDirPath);

        /**
         * We export the pages into the provided export directory.
         * While it may appear that the loop above is just the same
         * as with the loop below, it is intended as we need to process
         * ALL the pages and assets first before exporting, thus catching
         * errors along the way, killing the build process without really 
         * exporting any changes to the export directory. 
         */
        foreach ($this->pageRegistry->getPages() as $pageModel) {
            $this->exportPageContents($pageModel);
            $this->exportPageAssets($pageModel);
        }

        $this->pageRegistry->clearBin();
        $this->postBuildServices();
    }

    private function buildPageContents(
        PageModel $pageModel
    ){
        $templateName = $pageModel->getTemplateName();
        $templateModel = $this->templateRegistry->register($templateName);

        ob_start();
        include $this->themeController->getIndexPath();
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
        $templateModel = $this->templateRegistry->getTemplateModel($templateName);
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
        $templateModel = $this->templateRegistry->getTemplateModel($templateName);

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
        $pageDir = $this->exportDirPath.'/'.$pageModel->getDirPath().'/';
        if (!is_dir($pageDir)) mkdir($pageDir);

        $staticExtension = (AppSettings::build()->exportPageWithoutHTMLExtension() === true) ? '' : '.html';
        $exportPath = $pageDir.$pageModel->getName().$staticExtension;
        file_put_contents($exportPath,$pageModel->getPageHTML());
    }

    private function exportPageAssets(
        PageModel $pageModel
    ){
        $pageDirPath = ($pageModel->getDirPath()==='') ? '' : '/'.$pageModel->getDirPath(); 
        $assetsDir = $this->exportDirPath.'/assets'.$pageDirPath.'/';
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
