<?php 

namespace Kenjiefx\ScratchPHP\App\Assets\Static;

class StaticAssetFactory {

    public function __construct(
        
    ) {}

    /**
     * Creates a StaticAssetModel instance.
     *
     * @param string $fileName The file name of the static asset.
     * @return StaticAssetModel The created static asset model.
     */
    public function create(string $fileName): StaticAssetModel {
        return new StaticAssetModel($fileName);
    }

}