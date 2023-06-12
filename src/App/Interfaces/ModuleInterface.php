<?php 

namespace Kenjiefx\ScratchPHP\App\Interfaces;

interface ModuleInterface
{
    /**
     * Import module dependencies
     */
    public function prepare_dependencies();

    /**
     * Runs module
     */
    public function run_commands();
}