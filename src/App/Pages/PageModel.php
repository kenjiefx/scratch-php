<?php

namespace Kenjiefx\ScratchPHP\App\Pages;

class PageModel
{

    public function __construct(
        private string $page_id,
        private string $bin_reference_id,
        private string $page_name,
        private string $template_name,
        private string $dir_location,
        private string $page_title,
        private array $page_data
    ){

    }

    public function getTemplateName(){
        return $this->templateName;
    }

    public function setPageHTML(
        string $contentHTML
    ){
        file_put_contents($this->getBinPath('html'),$contentHTML);
    }

    public function setPageCSS(
        string $contentCSS
    ){
        file_put_contents($this->getBinPath('css'),$contentCSS);
    }

    public function setPageJS(
        string $contentJS
    ){
        file_put_contents($this->getBinPath('js'),$contentJS);
    }

    public function getPageHTML(){
        return file_get_contents($this->getBinPath('html'));
    }

    public function getPageCSS(){
        return file_get_contents($this->getBinPath('css'));
    }

    public function getPageJS(){
        return file_get_contents($this->getBinPath('js'));
    }

    public function getPageData(){
        return $this->data;
    }

    public function getDirPath(){
        return $this->dirPath;
    }

    public function getName(){
        return $this->name;
    }

    public function getId(){
        return $this->id;
    }

    public function getTitle(){
        return $this->title;
    }

    public function getBinPath(
        string $fileType
    ){
        return __dir__.'/bin/'.
            $this->binId.'.'.$fileType;
    }
}
