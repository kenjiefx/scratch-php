<?php

namespace Kenjiefx\ScratchPHP;

use Kenjiefx\ScratchPHP\App\Interfaces\ModuleInterface;
use Kenjiefx\ScratchPHP\App\Modules\CLIModule;
use Kenjiefx\ScratchPHP\App\Modules\PreviewModule;

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
        $this->Module->loadDependencies();
        $this->Module->runModule();
    }
}
