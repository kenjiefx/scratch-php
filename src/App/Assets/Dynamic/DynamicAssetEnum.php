<?php 

namespace Kenjiefx\ScratchPHP\App\Assets\Dynamic;

/**
 * DynamicAssetEnum is an enumeration class that defines constants for dynamic asset types.
 * These constants can be used to identify different types of dynamic assets in the application.
 */
enum DynamicAssetEnum {

    case CSS = 'css';
    case JS = 'js';

    /**
     * Returns the file extension associated with the asset type.
     *
     * @return string The file extension for the asset type.
     */
    public function getExtension(): string {
        return match ($this) {
            self::CSS => '.css',
            self::JS => '.js'
        };
    }

}