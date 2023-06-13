<?php

namespace Kenjiefx\ScratchPHP\App\Pages;
use Kenjiefx\ScratchPHP\App\Configuration\AppSettings;
use Kenjiefx\ScratchPHP\App\Templates\TemplateController;

class PageController
{
    public function __construct(
        private PageModel $PageModel
    ){

    }

    public function getPageTitle(){
        return $this->PageModel->getTitle();
    }

    public function getTemplate():TemplateController{
        return new TemplateController($this->PageModel->getTemplate());
    }

    public function getPageRelPath(){
        $dirPath = $this->PageModel->getDirPath();
        return ($dirPath==='') ? '' : '/'.$dirPath;
    }

    public function getPageId(){
        $this->PageModel->getId();
    }

    public function getAssetsName(){
        return (AppSettings::build()->useRandomAssetsFileNames()===true) ? 
            $this->PageModel->getId() : $this->PageModel->getName();
    }

    public function getPageData(){
        return $this->PageModel->getData();
    }

    public function setPageHtml(string $pageHtml){
        // foreach (AppSettings::extensions()->getExtension() as $ExtensionObject) {
        //     $pageHtml = $ExtensionObject->mutatePageHTML($pageHtml);
        // }
        $this->PageModel->setHtml($pageHtml);
    }

    public function setPageCss(string $pageCss) {
        // foreach (AppSettings::extensions()->getExtension() as $ExtensionObject) {
        //     $pageCss = $ExtensionObject->mutatePageCSS($pageCss);
        // }
        $this->PageModel->setCss($pageCss);
    }

    public function setPageJs(string $pageJs) {
        // foreach (AppSettings::extensions()->getExtension() as $ExtensionObject) {
        //     $pageJs = $ExtensionObject->mutatePageJS($pageJs);
        // }
        $this->PageModel->setJs($pageJs);
    }
}
