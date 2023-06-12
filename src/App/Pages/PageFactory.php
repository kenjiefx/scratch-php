<?php

namespace Kenjiefx\ScratchPHP\App\Pages;
use Kenjiefx\ScratchPHP\App\Exceptions\ConfigurationException;
use Kenjiefx\ScratchPHP\App\Pages\PageJSON;

/**
 * Handles the creation of the PageModel, because the PageModel itself requires
 * a lot of things to create. 
 */
class PageFactory
{

    private static int $page_id_incrementor = 1;

    public static function create(PageJSON $page_json):PageModel{
        $page_model = new PageModel(
            page_id: strval(Self::$page_id_incrementor++).uniqid(),
            bin_reference_id: self::create_bin_reference_id($page_json->get_relative_path(),$page_json->get_file_name()), 
            page_name: $page_json->get_file_name(),
            template_name: $page_json->get_template_name(),
            dir_location: $page_json->get_relative_path(),
            page_title: $page_json->get_page_title(),
            page_data: $page_json->get_page_data()
        );
        return $page_model;
    }

    public static function create_bin_reference_id(string $relative_path, string $file_name){
        return str_replace('/','.',$relative_path).'.'.$file_name;
    }


}
