<?php 

namespace Kenjiefx\ScratchPHP\App\Statics;

use Kenjiefx\ScratchPHP\App\Files\File;

class StaticAssetsModel {
    
    public function __construct(
        public readonly string $fileName,
        public readonly File $filePath
    ) {}

}