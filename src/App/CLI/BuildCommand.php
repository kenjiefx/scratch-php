<?php

namespace Kenjiefx\ScratchPHP\App\CLI;
use Kenjiefx\ScratchPHP\App\Interfaces\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Implementations\DistExporter\DistExportCleaner;
use Kenjiefx\ScratchPHP\App\Interfaces\PageCollectorInterface;
use Kenjiefx\ScratchPHP\App\Orchestrators\BuildOrchestrator;
use Kenjiefx\ScratchPHP\App\Pages\PageIterator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'build')]
class BuildCommand extends Command
{
    protected static $defaultDescription = 'Builds your app to the dist directory';

    public function __construct(
        private readonly CLIInputConfiguration $cliInputConfiguration,
        private readonly ConfigurationInterface $configurationInterface,
        private readonly PageCollectorInterface $pageCollectorInterface,
        private readonly DistExportCleaner $distExportCleaner,
        private readonly BuildOrchestrator $buildOrchestrator
    ) {
        parent::__construct();
    }

    protected function execute(
        InputInterface $input, 
        OutputInterface $output
        ): int
    {
        $this->setInputOverrides($input);
        $pageIterator = $this->getBuildablePages();
        $this->buildOrchestrator->build($pageIterator);
        return Command::SUCCESS;
    }

    public function getBuildablePages(): PageIterator {
        $page = $this->configurationInterface->pageToBuild();
        if ($page !== null) {
            return $this->pageCollectorInterface->collectByName($page);
        }
        $this->distExportCleaner->clearExportDir();
        return $this->pageCollectorInterface->collectAll();
    }

    public function setInputOverrides(
        InputInterface $input,
    ) {
        $this->cliInputConfiguration->setThemeName(
            $input->getOption('theme') ?? null
        );
        $this->cliInputConfiguration->setExportDir(
            $input->getOption('exportDir') ?? 'dist'
        );
        $this->cliInputConfiguration->setBaseUrl(
            $input->getOption('baseUrl') ?? '/'
        );
        $this->cliInputConfiguration->setPageToBuild(
            $input->getOption('page') ?? null
        );
    }

    protected function configure(): void
    {
        $this->setHelp('This command allows you build your app.')
             ->addOption('theme',null,InputOption::VALUE_OPTIONAL,'Build using a specific theme')
             ->addOption('exportDir',null,InputOption::VALUE_OPTIONAL,'Set export directory for the pages')
             ->addOption('page',null,InputOption::VALUE_OPTIONAL,'Build a specific page')
             ->addOption('baseUrl',null,InputOption::VALUE_OPTIONAL,'Base URL for the static app. Default is "/"');
    }


}
