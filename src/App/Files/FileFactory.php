<?php 

namespace Kenjiefx\ScratchPHP\App\Files;

class FileFactory {

    /**
     * Creates a File instance from a given path.
     *
     * @param string $path The path to the file.
     * @return File The File instance.
     */
    public static function create(string $path): File
    {
        return new File($path);
    }

}