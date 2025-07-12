<?php 

namespace Kenjiefx\ScratchPHP\App\CLI;

use Kenjiefx\ScratchPHP\App\Configurations\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Themes\ThemeGenerator;
use Kenjiefx\ScratchPHP\Container;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface; 

#[AsCommand(name: 'create:theme')]
class CreateThemeCommand extends Command {

    protected static $defaultDescription = 'Creates a new theme';

    protected function execute(
        InputInterface $input, 
        OutputInterface $output
        ): int
    {
        $configuration = Container::get()->get(ConfigurationInterface::class);
        $themeGenerator = Container::get()->get(ThemeGenerator::class);
        $themeName = $input->getArgument('theme_name');
        $themeGenerator->generate($themeName);
        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setHelp('This command allows you to create a new theme.')
             ->addArgument('theme_name', InputArgument::REQUIRED, 'What is the name of this theme (no spaces please)?');
    }

}