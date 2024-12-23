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

    /** 
     * Recursively crawls through the ROOT.'/pages' directory and looks for PageJSON 
     */
    public function discover(string|null $dirpath = null) {

        if ($dirpath === null) {
            $dirpath = ROOT . self::PAGES_DIR;
        }

        $filenames = array_filter(
            scandir(directory: $dirpath),
            function(string $filename): bool {
                return $filename !== '.' && $filename !== '..';
            }
        );

        foreach ($filenames as $filename) {
            $filepath = $dirpath . '/' . $filename;
            if (is_dir(filename: $filepath)) { 
                # Recursively discover pages
                $this->discover(dirpath: $filepath);
            } else {
                $this->register($filepath);
            }
        }
    }

    /**
     * Creates and registers a PageController into the registry
     * @param string $filepath
     * @throws \Kenjiefx\ScratchPHP\App\Exceptions\PageNotFoundException
     * @return void
     */
    public function register(string $filepath) {
        if (!file_exists($filepath)) {
            throw new PageNotFoundException($filepath);
        }
        $PageController 
            = new PageController(
                PageFactory::create(
                    new PageJSON($filepath)
                )
            );
        array_push($this->registry,$PageController);
    }

    public function get(): PageIterator {
        $PageIterator = new PageIterator($this->registry);
        return $PageIterator;
    }
}
