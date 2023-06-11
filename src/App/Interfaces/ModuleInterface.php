<?php 

namespace Kenjiefx\ScratchPHP\App\Interfaces;

interface ModuleInterface
{
    /**
     * Import module dependencies
     */
    public function importDependencies();

    /**
     * Runs module
     */
    public function runCommands();
}