<?php 

namespace Kenjiefx\ScratchPHP\App\Events\Instances;

use Kenjiefx\ScratchPHP\App\Events\EventInterface;
use Kenjiefx\ScratchPHP\App\Extensions\ExtensionSettings;

class ExtensionSettingsRegisterEvent implements EventInterface {

    public function __construct(
        public readonly ExtensionSettings $settings
    ) {}

}