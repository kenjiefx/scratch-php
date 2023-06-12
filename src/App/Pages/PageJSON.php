<?php

namespace Kenjiefx\ScratchPHP\App\Pages;
use Kenjiefx\ScratchPHP\App\Exceptions\MissingPageJsonFieldException;

/**
 * Represents each of the JSON file in the PageRegistry::$directory. Each
 * JSON file corresponds to a specific page and contains essential information 
 * such as the page title and additional data required during the build process.
 */
class PageJSON
{
    private string $template_name;
    private string $page_title;
    private array $page_data;
    private string $file_name;
    private string $relative_path;

    public function __construct(){

    }

    /**
     * Parses and validates Page JSON file.
     */
    public function unpack_page_json_from_path(string $page_json_path){

        $page_json_data = json_decode(file_get_contents($page_json_path),TRUE);

        if (!isset($page_json_data['template'])) throw new MissingPageJsonFieldException('template',$page_json_path);
        $this->template_name = $page_json_data['template'];

        if (!isset($page_json_data['title'])) throw new MissingPageJsonFieldException('title',$page_json_path);
        $this->page_title = $page_json_data['title'];

        if (!isset($page_json_data['data'])) throw new MissingPageJsonFieldException('data',$page_json_path);
        $this->page_data = $page_json_data['data'];

        $this->derive_metadata_from_path($page_json_data);

        return $this;
    }

    private function derive_metadata_from_path(string $page_json_path){
        $path_tokens        = explode('/',$page_json_path);
        $file_name_pos      = count($path_tokens) - 1;
        [$this->file_name,] = explode('.',$path_tokens[$file_name_pos])[0];
        array_shift($path_tokens);
        array_pop($path_tokens);
        $this->relative_path = implode('/',$path_tokens);
    }

    public function get_template_name(){
        return $this->template_name;
    }

    public function get_page_title(){
        return $this->page_title;
    }

    public function get_page_data(){
        return $this->page_data;
    }

    public function get_file_name(){
        return $this->file_name;
    }

    public function get_relative_path(){
        return $this->relative_path;
    }


}
