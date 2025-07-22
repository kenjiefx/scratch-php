<?php 

namespace Kenjiefx\ScratchPHP\App\Runners;

use Kenjiefx\ScratchPHP\App\CLI\BuildCommand;
use Kenjiefx\ScratchPHP\App\CLI\CLIInputConfiguration;
use Kenjiefx\ScratchPHP\App\CLI\CreateBlockCommand;
use Kenjiefx\ScratchPHP\App\CLI\CreateComponentCommand;
use Kenjiefx\ScratchPHP\App\CLI\CreateTemplateCommand;
use Kenjiefx\ScratchPHP\App\CLI\CreateThemeCommand;
use Kenjiefx\ScratchPHP\App\Implementations\DistExporter\DistExportService;
use Kenjiefx\ScratchPHP\App\Implementations\PageJSON\PageJSONCollector;
use Kenjiefx\ScratchPHP\App\Implementations\ScratchJSON\ScratchJSONExtensionCollector;
use Kenjiefx\ScratchPHP\App\Implementations\ThemeManager\ThemeService;
use Kenjiefx\ScratchPHP\App\Implementations\VanillaPHP\VanillaPHPBuilder;
use Kenjiefx\ScratchPHP\App\Interfaces\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Interfaces\ExtensionCollectorInterface;
use Kenjiefx\ScratchPHP\Container;
use PharIo\Manifest\Extension;
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
    public function loadDependencies() {
        Container::create();
        Container::bind()->ConfigurationInterface(Container::get(CLIInputConfiguration::class));
        Container::bind()->PageCollectorInterface(Container::get(PageJSONCollector::class));
        Container::bind()->ThemeServiceInterface(Container::get(ThemeService::class));
        Container::bind()->ExtensionCollectorInterface(Container::get(ScratchJSONExtensionCollector::class));
        Container::bind()->ExportServiceInterface(Container::get(DistExportService::class));
        Container::bind()->BuildServiceInterface(Container::get(VanillaPHPBuilder::class));
        Container::get(ConfigurationInterface::class)->loadConfig();
        Container::get(ExtensionCollectorInterface::class)->collect();
        $this->applicationRunner = new Application();
        $this->applicationRunner->add(Container::get(BuildCommand::class));
        $this->applicationRunner->add(Container::get(CreateBlockCommand::class));
        $this->applicationRunner->add(Container::get(CreateComponentCommand::class));
        $this->applicationRunner->add(Container::get(CreateTemplateCommand::class));
        $this->applicationRunner->add(Container::get(CreateThemeCommand::class));
    }

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