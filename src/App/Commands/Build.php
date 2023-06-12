<?php

namespace Kenjiefx\ScratchPHP\App\Commands;
use Kenjiefx\ScratchPHP\App\Build\BuildController;
use Kenjiefx\ScratchPHP\App\Factory\ContainerFactory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'build')]
class Build extends Command
{
    protected static $defaultDescription = 'Builds your app to the dist directory';

    protected function execute(
        InputInterface $input, 
        OutputInterface $output
        ): int
    {
        $buildController = ContainerFactory::create()->get(BuildController::class);
        $buildController->build();
        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setHelp('This command allows you build your app.');
    }


}
