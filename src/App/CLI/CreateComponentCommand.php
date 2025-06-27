<?php

namespace Kenjiefx\ScratchPHP\App\CLI;
use Kenjiefx\ScratchPHP\App\Components\ComponentGenerator;
use Kenjiefx\ScratchPHP\App\Components\ComponentService;
use Kenjiefx\ScratchPHP\App\Configurations\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Themes\ThemeModel;
use Kenjiefx\ScratchPHP\Container;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'create:component')]
class CreateComponentCommand extends Command
{
    protected static $defaultDescription = 'Creates a component in your theme';

    protected function execute(
        InputInterface $input, 
        OutputInterface $output
        ): int
    {
        $configuration = Container::get()->get(ConfigurationInterface::class);
        $themeName = $configuration->getThemeName();
        $themeModel = new ThemeModel($themeName);
        $componentName = $input->getArgument('component_name');
        $componentGenerator = Container::get()->get(ComponentGenerator::class);
        $componentGenerator->generate(
            $componentName, $themeModel
        );
        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setHelp('This command allows you to create a component in your theme.')
             ->addArgument('component_name', InputArgument::REQUIRED, 'What is the name of this component (no spaces please)?')
             ->addOption('clean',null,InputOption::VALUE_NONE,'Create component without modification from your extensions?');
    }


}