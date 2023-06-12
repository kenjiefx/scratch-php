<?php

namespace Kenjiefx\ScratchPHP\App\Commands\Create;
use Kenjiefx\ScratchPHP\App\Configuration\AppSettings;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'create:theme')]
class Theme extends Command
{
    protected static $defaultDescription = 'Creates a new theme';

    protected function execute(
        InputInterface $input, 
        OutputInterface $output
        ): int
    {
        $name_from_argument = $input->getArgument('theme_name');
        $is_new_settings = AppSettings::create($name_from_argument);
        AppSettings::load();

        $theme_name = $is_new_settings ? AppSettings::get_theme_name_from_config() : $name_from_argument;
        $ThemeController = new ThemeController();
        $ThemeController->create_theme($theme_name);
        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setHelp('This command allows you to create a new theme in your project.')
             ->addArgument('theme_name', InputArgument::REQUIRED, 'What is the name of this theme?');
    }
}
