<?php 

namespace Kenjiefx\ScratchPHP\App\Interfaces;

use Kenjiefx\ScratchPHP\App\Pages\PageIterator;

interface PageCollectorInterface {

    /**
     * Collect all pages.
     *
     * @return PageIterator
     */
    public function collectAll(): PageIterator;

    /**
     * Collect pages by their name. 
     * This name can be full path, relative path with extension,
     * or just the name of the page. Depending on the implementation,
     * as long as PageIterator is returned.
     * @param string $name
     * @return PageIterator
     */
    public function collectByName(string $name): PageIterator;

    /**
     * Check if a page with the given name exists.
     * @param string $name
     * @return void
     */
    public function doesExist(string $name): bool;

}