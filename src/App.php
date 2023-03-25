<?php

namespace Kenjiefx\ScratchPHP;

use Kenjiefx\ScratchPHP\App\Modules\CLIModule;
use Kenjiefx\ScratchPHP\App\Modules\PreviewModule;
use Kenjiefx\ScratchPHP\App\Modules\ModuleInterface;

class App
{
    private ModuleInterface $Module;

    public function __construct()
    {
        $this->Module = (php_sapi_name() !== 'cli') ?
            new PreviewModule() : new CLIModule();
    }

    public function run()
    {
        $this->Module->importDependencies();
        $this->Module->runCommands();
    }
}
