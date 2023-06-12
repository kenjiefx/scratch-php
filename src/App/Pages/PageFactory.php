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

    private static int $page_id_incrementor = 0;

    public static function create(PageJSON $page_json):PageModel{
        $page_model = new PageModel(
            page_id: strval(Self::$page_id_incrementor++),
            bin_reference_id: self::createBinId($dirPath,$fileName), 
            page_name: $page_json->,
            template_name: $page_json->get_template_name(),
            dir_location: ,
            page_title: $page_json->get_page_title(),
            page_data: $page_json->get_page_data();
        );
        return $pageModel;
    }

    public static function createBinId(
        string $dirPath,
        string $fileName
    ){
        return str_replace('/','.',$dirPath).'.'.$fileName;
    }


}
