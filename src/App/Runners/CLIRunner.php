<?php 

namespace Kenjiefx\ScratchPHP\App\Runners;

use Symfony\Component\Console\Application;

/**
 * CLIRunner class implements the RunnerInterface for handling command line interface (CLI) execution.
 * This runner is used when the application is running in a command line context.
 */
class CLIRunner implements RunnerInterface
{

    private Application $applicationRunner;

    /**
     * Load dependencies required for the CLI runtime context.
     *
     * @return void
     */
    public function loadDependencies() {}

    /**
     * Execute the CLI context-specific logic.
     *
     * @return void
     */
    public function executeContext() {
        // Handle command line arguments and execute the appropriate commands
        // This could involve parsing arguments, executing commands, etc.
        $this->applicationRunner->run();
    }

}