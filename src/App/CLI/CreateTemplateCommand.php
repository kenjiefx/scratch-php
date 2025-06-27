<?php

namespace Kenjiefx\ScratchPHP\App\CLI;
use Kenjiefx\ScratchPHP\App\Configurations\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Templates\TemplateGenerator;
use Kenjiefx\ScratchPHP\App\Themes\ThemeModel;
use Kenjiefx\ScratchPHP\Container;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'create:template')]
class CreateTemplateCommand extends Command
{
    protected static $defaultDescription = 'Creates a template in your theme';

    protected function execute(
        InputInterface $input, 
        OutputInterface $output
        ): int
    {
        $configuration = Container::get()->get(ConfigurationInterface::class);
        $themeName = $configuration->getThemeName();
        $themeModel = new ThemeModel($themeName);
        $templateName = $input->getArgument('template_name');
        $templateGenerator = Container::get()->get(TemplateGenerator::class);
        $templateGenerator->generate(
            $templateName, $themeModel
        );
        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setHelp('This command allows you to create a template in your theme.')
             ->addArgument('template_name', InputArgument::REQUIRED, 'What is the name of this template (no spaces please)?');
    }
}
