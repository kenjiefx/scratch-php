<?php 

namespace Kenjiefx\ScratchPHP\App\Exports;

/**
 * ExportModel class represents a model for exporting data. 
 * It encapsulates the file and content to be exported.
 */
class ExportModel {

    public function __construct(
        public readonly string $relativePath,
        public readonly string $content
    ){}

}