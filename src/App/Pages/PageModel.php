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

    public function get_template_name(){
        return $this->template_name;
    }

    public function set_html(string $html_content){
        file_put_contents($this->generate_bin_path('html'), $html_content);
    }

    public function set_css(string $css_content){
        file_put_contents($this->generate_bin_path('css'),$css_content);
    }

    public function set_javascript(string $javascript_content){
        file_put_contents($this->generate_bin_path('js'),$javascript_content);
    }

    public function get_html(){
        return file_get_contents($this->generate_bin_path('html'));
    }

    public function get_css(){
        return file_get_contents($this->generate_bin_path('css'));
    }

    public function get_javascript(){
        return file_get_contents($this->generate_bin_path('js'));
    }

    public function get_data(){
        return $this->page_data;
    }

    public function get_directory_path(){
        return $this->dir_location;
    }

    public function get_name(){
        return $this->page_name;
    }

    public function get_id(){
        return $this->page_id;
    }

    public function get_title(){
        return $this->page_title;
    }

    public function generate_bin_path(string $file_type){
        return __dir__.'/bin/'.$this->bin_reference_id.'.'.$file_type;
    }
}
