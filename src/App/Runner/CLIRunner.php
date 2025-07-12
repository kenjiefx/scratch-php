<?php 

namespace Kenjiefx\ScratchPHP\App\Runner;

use Directory;
use Kenjiefx\ScratchPHP\App\CLI\BuildCommand;
use Kenjiefx\ScratchPHP\App\CLI\CreateBlockCommand;
use Kenjiefx\ScratchPHP\App\CLI\CreateComponentCommand;
use Kenjiefx\ScratchPHP\App\CLI\CreateTemplateCommand;
use Kenjiefx\ScratchPHP\App\CLI\CreateThemeCommand;
use Kenjiefx\ScratchPHP\App\Configurations\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Configurations\ScratchJsonConfiguration;
use Kenjiefx\ScratchPHP\App\Directories\DirectoryService;
use Kenjiefx\ScratchPHP\App\Exports\DistExporter;
use Kenjiefx\ScratchPHP\App\Exports\ExporterInterface;
use Kenjiefx\ScratchPHP\App\Extensions\ScratchJsonExtManager;
use Kenjiefx\ScratchPHP\App\Files\FileFactory;
use Kenjiefx\ScratchPHP\App\Files\FileService;
use Kenjiefx\ScratchPHP\App\Templates\TemplateService;
use Kenjiefx\ScratchPHP\App\Templates\TemplateServiceInterface;
use Kenjiefx\ScratchPHP\App\Themes\ThemeService;
use Kenjiefx\ScratchPHP\Container;
use League\Container\ReflectionContainer;
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
    public function loadDependencies()
    {
        Container::get()->delegate(new ReflectionContainer());
        Container::get()->add(ConfigurationInterface::class, new ScratchJsonConfiguration());
        Container::get()->get(ScratchJsonExtManager::class)->load();
        $this->initTemplateService();
        $this->initDistExporter();
        $this->applicationRunner = new Application();
        $this->applicationRunner->add(new BuildCommand());
        $this->applicationRunner->add(new CreateComponentCommand());
        $this->applicationRunner->add(new CreateBlockCommand());
        $this->applicationRunner->add(new CreateTemplateCommand());
        $this->applicationRunner->add(new CreateThemeCommand());
    }

    /**
     * Execute the CLI context-specific logic.
     *
     * @return void
     */
    public function executeContext()
    {
        // Handle command line arguments and execute the appropriate commands
        // This could involve parsing arguments, executing commands, etc.
        $this->applicationRunner->run();
    }

    /**
     * Initialize the TemplateService for rendering templates in the CLI context.
     * @return void
     */
    private function initTemplateService()
    {
        // Initialize the template service for CLI context if needed
        Container::get()->add(
            TemplateServiceInterface::class,
            new TemplateService(
                Container::get()->get(ConfigurationInterface::class),
                Container::get()->get(ThemeService::class),
                Container::get()->get(FileFactory::class)
            )
        );
    }

    /**
     * Initialize the DistExporter for exporting files in the CLI context.
     * @return void
     */
    private function initDistExporter(){
        Container::get()->add(
            ExporterInterface::class,
            new DistExporter(
                Container::get()->get(ConfigurationInterface::class),
                Container::get()->get(FileService::class),
                Container::get()->get(FileFactory::class),
                Container::get()->get(DirectoryService::class)
            )
        );
    }
}