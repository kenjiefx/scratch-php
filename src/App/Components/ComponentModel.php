<?php

namespace Kenjiefx\ScratchPHP\App\Components;

class ComponentModel
{
    private string $html;
    private string $css;
    private string $js;

    public function __construct(
        private string $name
    ){

    }

    public function getName(){
        return $this->name;
    }

    public function getHtml(){
        return $this->html;
    }

    public function setHtml(string $html) {
        $this->html = $html;
    }

    public function setCss(string $css) {
        $this->css = $css;
    }

    public function getCss(){
        return $this->css;
    }

    public function setJavascript(string $javascript){
        $this->js = $javascript;
    }

    public function getJavascript(){
        return $this->js;
    }
}
