<?php 

namespace Kenjiefx\ScratchPHP\App\Extensions;

class ExtensionModel {

    public function __construct(
        /**
         * The PSR-4 fully qualified name of the extension class.
         */
        public readonly string $fullyQualifiedName,
        /**
         * Settings for the extension.
         */
        public readonly ExtensionSettings $settings
    ) {}

}