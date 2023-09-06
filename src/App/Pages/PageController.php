<?php

namespace Kenjiefx\ScratchPHP\App\Pages;
use Kenjiefx\ScratchPHP\App\Configuration\AppSettings;
use Kenjiefx\ScratchPHP\App\Events\EventDispatcher;
use Kenjiefx\ScratchPHP\App\Events\OnBuildCssEvent;
use Kenjiefx\ScratchPHP\App\Events\OnBuildHtmlEvent;
use Kenjiefx\ScratchPHP\App\Events\OnBuildJsEvent;
use Kenjiefx\ScratchPHP\App\Templates\TemplateController;

class PageController
{
    private EventDispatcher $EventDispatcher;

    public function __construct(
        private PageModel $PageModel
    ){
        $this->EventDispatcher = new EventDispatcher;
    }

    public function getPageName(){
        return $this->PageModel->getName();
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
        return $this->PageModel->getId();
    }

    public function getAssetsName(){
        return (AppSettings::build()->useRandomAssetsFileNames()===true) ? 
            $this->PageModel->getId() : $this->PageModel->getName();
    }

    public function getPageData(){
        return $this->PageModel->getData();
    }

    public function addPageData(string $key,mixed $value){
        $this->PageModel->addPageData($key,$value);
    }

    public function setPageHtml(string $pageHtml){
        $modifiedHtml = $this->EventDispatcher->dispatchEvent(OnBuildHtmlEvent::class,$pageHtml);
        if ($modifiedHtml!==null) $pageHtml = $modifiedHtml;
        $this->PageModel->setHtml($pageHtml);
    }

    public function setPageCss(string $pageCss) {
        $modifiedCss = $this->EventDispatcher->dispatchEvent(OnBuildCssEvent::class,$pageCss);
        if ($modifiedCss!==null) $pageCss = $modifiedCss;
        $this->PageModel->setCss($pageCss);
    }

    public function setPageJs(string $pageJs) {
        $modifiedJs = $this->EventDispatcher->dispatchEvent(OnBuildJsEvent::class,$pageJs);
        if ($modifiedJs!==null) $pageJs = $modifiedJs;
        $this->PageModel->setJs($pageJs);
    }

    public function getPageHtml(){
        return $this->PageModel->getHtml();
    }

    public function getPageCss(){
        return $this->PageModel->getCss();
    }

    public function getPageJs(){
        return $this->PageModel->getJavascript();
    }
}
