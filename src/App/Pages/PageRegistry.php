<?php

namespace Kenjiefx\ScratchPHP\App\Pages;
use Kenjiefx\ScratchPHP\App\Exceptions\PageNotFoundException;

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
    public const PAGES_DIR = '/pages';

    /** An list of paths to page.json files found in the PAGES_DIR */
    private array $registry = [];

    public function __construct(){
        
    }

    /** Recursively crawls through the ROOT.'/pages' directory and looks for PageJSON */
    public function discover(string|null $dirPath=null){
        if ($dirPath===null) {
            $dirPath = ROOT.self::PAGES_DIR;
        }
        foreach (scandir($dirPath) as $fileName) {
            if ($fileName==='.'||$fileName==='..') continue;
            $filePath = $dirPath.'/'.$fileName;
            if (is_dir($filePath)) { 
                $this->discover($filePath);
            } else {
                array_push($this->registry,$filePath);
            }
        }
    }

    public function register(string $filePath){
        if (!file_exists($filePath)) {
            throw new PageNotFoundException($filePath);
        }
        array_push($this->registry,$filePath);
    }

    public function getPages(): PageIterator{
        $PageIterator = new PageIterator($this->registry);
        return $PageIterator;
    }
}
