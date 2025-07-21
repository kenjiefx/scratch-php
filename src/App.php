<?php

namespace Kenjiefx\ScratchPHP;

use Kenjiefx\ScratchPHP\App\Runners\HTTPRunner;
use Kenjiefx\ScratchPHP\App\Runners\CLIRunner;
use Kenjiefx\ScratchPHP\App\Runners\RunnerInterface;

class App
{
    private RunnerInterface $runner;

    public function __construct(
        RunnerInterface $runner = null
    ) {
        // If a specific runner is provided, use it
        if ($runner !== null) {
            $this->runner = $runner;
            return;
        }
        // If no specific runner is provided, determine the context 
        // and instantiate the appropriate runner
        $this->runner = (php_sapi_name() !== 'cli') ?
            new HTTPRunner() : new CLIRunner() ;
    }

    public function run()
    {
        $this->runner->loadDependencies();
        $this->runner->executeContext();
    }
}
