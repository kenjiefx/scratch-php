<?php 

namespace Kenjiefx\ScratchPHP\App\Files;

/**
 * A simple representation of a file with its path.
 */
class File {

    public function __construct(
        public readonly string $path
    ){

    }

}