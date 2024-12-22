<?php

namespace Kenjiefx\ScratchPHP\App\Export;
use Kenjiefx\ScratchPHP\App\Configuration\AppSettings;
use Kenjiefx\ScratchPHP\App\Pages\PageController;

class ExportService
{

    public function __construct(
        private ExportObject $ExportObject
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

    public function html(){

        $pagedir = $this->toRealPath(
            $this->ExportObject->PageController->relpath()
        );
        
        # Creates page director if not existing
        if (!is_dir($pagedir)) {
            mkdir($pagedir,0755,true);
        }

        # Adds trailer slash to directory path if not existing
        if (substr($pagedir, -1) !== '/') 
            $pagedir = $pagedir . '/';
        
        $extension = (AppSettings::build()->exportPageWithoutHTMLExtension() === true) ? '' : '.html';
        $filename = $this->ExportObject->PageController->PageModel->name;
        $filepath = $pagedir.$filename.$extension;

        # Finally, we export the content to the designated path
        file_put_contents(
            $filepath,
            $this->ExportObject->get()
        );
        
    }

    public function css(){
        $this->assets('.css');
    }

    public function js(){
        $this->assets('.js');
    }

    private function assets(string $extension): void {
        $assetsdir = $this->getassdir();
        
        # Creates the asset directory if not existing
        if (!is_dir($assetsdir)) {
            mkdir($assetsdir,0755,true);
        }

        $filename = $this->ExportObject->PageController->assetref();

        file_put_contents(
            $assetsdir . $filename . $extension,
            $this->ExportObject->get()
        );

    }

    public static function getdir(){
        return ROOT.AppSettings::getExportDirPath().'/';
    }

    public function getassdir(): string {
        $dir = ExportService::getdir() . 'assets';
        if (!is_dir($dir)) mkdir($dir);
        $pagedir = $this->ExportObject->PageController->relpath();
        return ($pagedir === '')  ?
            $dir . '/' : 
            $dir . $pagedir . '/';
    }

    private function toRealPath(string $relpath){
        if ($relpath !== '') {
            $relpath = ltrim($relpath,$relpath[0]);
        }
        return ExportService::getdir().$relpath;
    }


}
