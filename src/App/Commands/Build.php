<?php

namespace Kenjiefx\ScratchPHP\App\Commands;
use Kenjiefx\ScratchPHP\App\Build\BuildService;
use Kenjiefx\ScratchPHP\App\Factory\ContainerFactory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Kenjiefx\ScratchPHP\App\Export\ExportService;

#[AsCommand(name: 'build')]
class Build extends Command
{
    protected static $defaultDescription = 'Builds your app to the dist directory';

    protected function execute(
        InputInterface $input, 
        OutputInterface $output
        ): int
    {
        $BuildService = ContainerFactory::create()->get(BuildService::class);
        $BuildService->start([
            'buildMode' => $input->getOption('buildMode') ?? 'default',
            'pagePath' => $input->getOption('page') ?? null
        ]);
        $BuildService->completeBuild();
        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setHelp('This command allows you build your app.')
             ->addOption('buildMode',null,InputOption::VALUE_OPTIONAL,'Create component without modification from your extensions?')
             ->addOption('page',null,InputOption::VALUE_OPTIONAL,'Create component without modification from your extensions?');
    }


}
