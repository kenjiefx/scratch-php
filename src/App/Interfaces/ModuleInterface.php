<?php 

namespace Kenjiefx\ScratchPHP\App\Interfaces;

interface ModuleInterface
{
    /**
     * Import module dependencies
     */
    public function loadDependencies();

    /**
     * Runs module
     */
    public function runModule();
}