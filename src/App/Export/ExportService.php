<?php

namespace Kenjiefx\ScratchPHP\App\Export;
use Kenjiefx\ScratchPHP\App\Configuration\AppSettings;
use Kenjiefx\ScratchPHP\App\Pages\PageController;

class ExportService
{

    public function __construct(
        private PageController $PageController
    ){

    }

    public static function clearExportDir(string $exportDirPath){
        foreach (scandir($exportDirPath) as $file) {
            if ($file==='.'||$file==='..') continue;
            $path = $exportDirPath.'/'.$file;
            if (is_dir($path)) {
                ExportService::clearExportDir($path);
                rmdir($path);
            } else {
                unlink($path);
            }
        }
    }

    public function pageHtml(){
        $pageDir = $this->relativeToRealPath($this->PageController->getPageRelPath());
        
        if (!is_dir($pageDir)) {
            mkdir($pageDir);
        }

        if (substr($pageDir, -1)!=='/') $pageDir = $pageDir.'/';

        $fileExt  = (AppSettings::build()->exportPageWithoutHTMLExtension() === true) ? '' : '.html';
        $filePath = $pageDir.$this->PageController->getPageName().$fileExt;

        file_put_contents($filePath,$this->PageController->getPageHtml());
        
    }

    public function pageAssets(){

        $assetsDir = ExportService::getExportDirPath().'assets';
        if (!is_dir($assetsDir)) mkdir($assetsDir);

        $relPageDir    = $this->PageController->getPageRelPath();
        $pageAssetsDir = ($relPageDir==='') ? $assetsDir.'/' : $assetsDir.$relPageDir.'/';

        if (!is_dir($pageAssetsDir)) mkdir($pageAssetsDir);

        $assetFileName = (AppSettings::build()->useRandomAssetsFileNames()) 
            ? $this->PageController->getPageId() : $this->PageController->getPageName();
        
        $cssExportPath = $pageAssetsDir.$assetFileName.'.css';
        file_put_contents($cssExportPath,$this->PageController->getPageCss());

        $jsExportPath = $pageAssetsDir.$assetFileName.'.js';
        file_put_contents($jsExportPath,$this->PageController->getPageJs());

    }

    public static function getExportDirPath(){
        return ROOT.AppSettings::getExportDirPath().'/';
    }

    private function relativeToRealPath(string $relPath){
        if ($relPath!=='') {
            $relPath = ltrim($relPath,$relPath[0]);
        }
        return ExportService::getExportDirPath().$relPath;
    }


}
