<?php

namespace Kenjiefx\ScratchPHP\App\Commands\Create;
use Kenjiefx\ScratchPHP\App\Configuration\AppSettings;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name:'create:theme')]
class Theme extends Command
{
    protected static $defaultDescription = 'Creates a new theme';

    protected function execute(
        InputInterface $input, 
        OutputInterface $output
        ): int
    {
        $themeName = $input->getArgument('theme_name');
        $isNewSettings = AppSettings::create($themeName);
        AppSettings::load();

        $themeName = $isNewSettings ? AppSettings::getThemeName() : $themeName;
        ThemeController::create($themeName);
        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setHelp('This command allows you to create a new theme in your project.')
             ->addArgument('theme_name', InputArgument::REQUIRED, 'What is the name of this theme?');
    }
}
