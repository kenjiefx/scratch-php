<?php 

namespace Kenjiefx\ScratchPHP\App\Assets\Static;

class StaticAssetModel {

    public function __construct(
        /**
         * The file name of the static asset.
         * @var string
         */
        public readonly string $fileName
    ) {}

}