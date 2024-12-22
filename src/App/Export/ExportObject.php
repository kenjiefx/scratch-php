<?php

namespace Kenjiefx\ScratchPHP\App\Export;

use Kenjiefx\ScratchPHP\App\Pages\PageController;

/**
 * Any object or file that can be exported
 */
class ExportObject {

    private string $contents;

    public function __construct(
        public readonly PageController $PageController
    ){
        
    }

    /**
     * Sets content of the object to be exported
     * @param string $contents
     * @return void
     */
    public function set(string $contents){
        $this->contents = $contents;
    }

    /**
     * Gets the content of the object to be exported
     * @return string
     */
    public function get(){
        return $this->contents;
    }

}