<?php 

namespace Kenjiefx\ScratchPHP\App\Runners;

/**
 * Runners are responsible for executing the context-specific logic 
 * and loading any necessary dependencies. This interface defines the 
 * methods that any runner must implement to ensure consistent behavior
 * across different runtime contexts (e.g., HTTP, CLI).
 */
interface RunnerInterface {

    /**
     * Load dependencies required for the runtime context.
     *
     * @return void
     */
    public function loadDependencies();

    /**
     * Execute the context-specific logic.
     *
     * @return void
     */
    public function executeContext();
    
}