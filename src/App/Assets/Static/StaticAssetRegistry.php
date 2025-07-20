<?php 

namespace Kenjiefx\ScratchPHP\App\Assets\Static;

class StaticAssetRegistry {

    /**
     * An associative array to hold registered static asset models.
     * The key is the file name of the asset, and the value is the StaticAssetModel.
     * 
     * @var array<string, StaticAssetModel>
     */
    private array $assets = [];

    public function __construct(
        
    ) {}

    /**
     * Registers a static asset model.
     * 
     * @param StaticAssetModel $assetModel The static asset model to register.
     */
    public function register(StaticAssetModel $assetModel): void {
        $fileName = $assetModel->fileName;
        if (!isset($this->assets[$fileName])) {
            $this->assets[$fileName] = $assetModel;
        }
    }

}