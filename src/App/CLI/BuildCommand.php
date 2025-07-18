<?php

namespace Kenjiefx\ScratchPHP\App\CLI;
use Kenjiefx\ScratchPHP\App\Builders\BuildService;
use Kenjiefx\ScratchPHP\App\Configurations\ConfigurationInterface;
use Kenjiefx\ScratchPHP\Container;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'build')]
class BuildCommand extends Command
{
    protected static $defaultDescription = 'Builds your app to the dist directory';

    protected function execute(
        InputInterface $input, 
        OutputInterface $output
        ): int
    {
        $preBuildService = Container::get()->get(PreBuildService::class);
        $pageRegistry = $preBuildService->createPageRegistry([
            'pagePath' => $input->getOption('page') ?? null
        ]);
        $this->mountInputOverrides($input);
        $buildService = Container::get()->get(BuildService::class);
        $buildService->build($pageRegistry);
        return Command::SUCCESS;
    }

    private function mountInputOverrides(
        InputInterface $input
    ) {
        $consoleInputConfg = Container::get()->get(ConfigurationInterface::class);
        $consoleInputConfg->setBaseUrl(
            $input->getOption('baseUrl') ?? '/'
        );
    }

    protected function configure(): void
    {
        $this->setHelp('This command allows you build your app.')
             ->addOption('buildMode',null,InputOption::VALUE_OPTIONAL,'Create component without modification from your extensions?')
             ->addOption('page',null,InputOption::VALUE_OPTIONAL,'Create component without modification from your extensions?')
             ->addOption('baseUrl',null,InputOption::VALUE_OPTIONAL,'Base URL for the static app. Default is "/"');
    }


}
