<?php

namespace Kenjiefx\ScratchPHP\App\Modules;

use Kenjiefx\ScratchPHP\App\Commands\Build;
use Kenjiefx\ScratchPHP\App\Commands\Create\Component;
use Kenjiefx\ScratchPHP\App\Commands\Create\Template;
use Kenjiefx\ScratchPHP\App\Commands\Deploy;
use Kenjiefx\ScratchPHP\App\Configuration\AppSettings;
use Kenjiefx\ScratchPHP\App\Configuration\CommandsRegistry;
use Kenjiefx\ScratchPHP\App\Factory\ContainerFactory;
use Kenjiefx\ScratchPHP\App\Interfaces\ModuleInterface;
use Kenjiefx\ScratchPHP\Container;
use Kenjiefx\ScratchPHP\App\Commands\Create\Theme;
use Symfony\Component\Console\Application;

class CLIModule implements ModuleInterface
{

    private Application $ConsoleApplication;
    private Container $AppContainer;

    public function loadDependencies() 
    {
        $this->ConsoleApplication = new Application();
        $this->ConsoleApplication->add(new Build());
        $this->ConsoleApplication->add(new Component());
        $this->ConsoleApplication->add(new Template());
        $this->ConsoleApplication->add(new Theme());
        $this->ConsoleApplication->add(new Deploy());
        CommandsRegistry::console($this->ConsoleApplication);
        $this->AppContainer = new Container(ContainerFactory::create());
        $this->AppContainer->register();
    }

    public function runModule()
    {
        AppSettings::load();
        $this->ConsoleApplication->run();
    }
}
