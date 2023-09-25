<?php

namespace Kenjiefx\ScratchPHP\App\Commands;
use Kenjiefx\ScratchPHP\App\Build\BuildService;
use Kenjiefx\ScratchPHP\App\Configuration\AppSettings;
use Kenjiefx\ScratchPHP\App\Events\EventDispatcher;
use Kenjiefx\ScratchPHP\App\Events\OnDeployEvent;
use Kenjiefx\ScratchPHP\App\Factory\ContainerFactory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'deploy')]
class Deploy extends Command
{
    protected static $defaultDescription = 'Deploy your app with the use of extensions';

    protected function execute(
        InputInterface $input, 
        OutputInterface $output
        ): int
    {
        AppSettings::load();
        $EventDispatcher = new EventDispatcher;
        $EventDispatcher->dispatchEvent(OnDeployEvent::class,ROOT.AppSettings::getExportDirPath());
        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setHelp('This command allows you to deploy your app with the use of extensions.');
    }


}
