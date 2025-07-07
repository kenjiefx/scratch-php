<?php 

namespace Kenjiefx\ScratchPHP\App\Collectibles;

use Kenjiefx\ScratchPHP\App\Files\File;

/**
 * CollectibleModel class represents a model for collectibles.
 */
class CollectibleModel {

    public function __construct(
        public readonly File $file
    ) {}

}