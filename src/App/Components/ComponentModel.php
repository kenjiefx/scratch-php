<?php

namespace Kenjiefx\ScratchPHP\App\Components;

class ComponentModel
{
    public function __construct(
        private string $name, 
        private string $template_name, 
        private string $html_path,
        private string $js_path,
        private string $css_path
    ){

    }

    public function get_component_html_path(){
        return $this->html_path;
    }

    public function get_component_name(){
        return $this->name;
    }
}
