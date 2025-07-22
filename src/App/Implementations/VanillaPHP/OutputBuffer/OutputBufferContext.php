<?php 

namespace Kenjiefx\ScratchPHP\App\Implementations\VanillaPHP\OutputBuffer;

use Kenjiefx\ScratchPHP\App\Pages\PageModel;

/**
 * OutputBufferContext is a simple data container that holds data that 
 * can be retrieved later during the output buffering process.
 */
class OutputBufferContext {

    public function __construct(
        public readonly PageModel $pageModel
    ) {}

}