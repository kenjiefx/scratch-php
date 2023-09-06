<?php

namespace Kenjiefx\ScratchPHP\App\Pages;
use Kenjiefx\ScratchPHP\App\Templates\TemplateModel;

class PageModel
{

    private string $html;
    private string $css;
    private string $javascript;

    public function __construct(
        private string $pageId,
        private string $pageName,
        private TemplateModel $TemplateModel,
        private string $dirPath,
        private string $pageTitle,
        private array $pageData
    ){

    }

    public function getTemplate(): TemplateModel{
        return $this->TemplateModel;
    }

    public function setHtml(string $html){
        $this->html = $html;
    }

    public function setCss(string $css){
        $this->css = $css;
    }

    public function setJs(string $javascript){
        $this->javascript = $javascript;
    }

    public function addPageData(string $key,mixed $value){
        $this->pageData[$key] = $value;
    }

    public function getHtml(){
        return $this->html;
    }

    public function getCss(){
        return $this->css;
    }

    public function getJavascript(){
        return $this->javascript;
    }

    public function getData(){
        return $this->pageData;
    }

    public function getDirPath(){
        return $this->dirPath;
    }

    public function getName(){
        return $this->pageName;
    }

    public function getId(){
        return $this->pageId;
    }

    public function getTitle(){
        return $this->pageTitle;
    }

}
