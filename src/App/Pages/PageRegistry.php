<?php

namespace Kenjiefx\ScratchPHP\App\Pages;

/** 
 * Registers and manages the pages of your static website. 
 * This object holds an array of Page Models.
 */
class PageRegistry
{

    /**
     * By default, pages are referenced by JSON files stored in ROOT.'/pages' directory. 
     * Each JSON file corresponds to a specific page and contains essential information 
     * such as the page title and additional data required during the build process.
     */
    private static string $directory = '\pages';

    /**
     * A collection of PageModels, populated by the PageFactory object. 
     * Please see @PageModel and @PageFactory for more information.
     */
    private static array $array_of_page_models;

    public function __construct(
        private PageFactory $PageFactory,
        private PageJSON $PageJSON
    ){
        if (!isset(static::$array_of_page_models)) {
            static::$array_of_page_models = [];
        }
    }

    /**
     * Recursively crawls through the ROOT.'/pages' directory and looks for PageJSON,
     * and registers PageModel using the PageFactory.
     */
    public function discover(string|null $path_to_discover=null){

        if ($path_to_discover===null) $path_to_discover = ROOT.static::$directory;

        $files = scandir($path_to_discover);
        foreach ($files as $file_name) {

            if ($file_name==='.'||$file_name==='..') continue;
            $file_path = $path_to_discover.'/'.$file_name;
            
            # If the file_path is another directory, we'll loop through the directory
            if (is_dir($file_path)) return $this->discover($file_path);

            # Parses and validates the page json file
            $page_json = (new PageJSON())->unpack_page_json_from_path($file_path);

            array_push(static::$array_of_page_models,PageFactory::create($page_json));

        }

    }

    public function clear_bin(){
        @array_map('unlink', array_filter((array) glob(__dir__."/bin/*") ) );
        file_put_contents(__dir__.'/bin/README.md','');
    }

    public function get_registered_pages_as_page_models(){
        return static::$array_of_page_models;
    }
}
