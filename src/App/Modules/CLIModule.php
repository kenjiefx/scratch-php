<?php

namespace Kenjiefx\ScratchPHP\App\Modules;

use Kenjiefx\ScratchPHP\App\Commands\Build;
use Kenjiefx\ScratchPHP\App\Commands\Create\Component;
use Kenjiefx\ScratchPHP\App\Commands\Create\Template;
use Kenjiefx\ScratchPHP\App\Factory\ContainerFactory;
use Kenjiefx\ScratchPHP\App\Interfaces\ModuleInterface;
use Kenjiefx\ScratchPHP\Container;
use Kenjiefx\ScratchPHP\App\Commands\Create\Theme;
use Symfony\Component\Console\Application;

class CLIModule implements ModuleInterface
{

    private Application $ConsoleApplication;
    private Container $AppContainer;

    public function importDependencies() 
    {
        $this->ConsoleApplication = new Application();
        $this->ConsoleApplication->add(new Build());
        $this->ConsoleApplication->add(new Component());
        $this->ConsoleApplication->add(new Template());
        $this->ConsoleApplication->add(new Theme());
        $this->AppContainer = new Container(ContainerFactory::create());
        $this->AppContainer->register();
    }

    public function runCommands()
    {
        $this->ConsoleApplication->run();
    }
}
