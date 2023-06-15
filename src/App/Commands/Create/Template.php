<?php

namespace Kenjiefx\ScratchPHP\App\Commands\Create;
use Kenjiefx\ScratchPHP\App\Configuration\AppSettings;
use Kenjiefx\ScratchPHP\App\Factory\ContainerFactory;
use Kenjiefx\ScratchPHP\App\Templates\TemplateController;
use Kenjiefx\ScratchPHP\App\Templates\TemplateModel;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'create:template')]
class Template extends Command
{
    protected static $defaultDescription = 'Creates a template in your theme';

    protected function execute(
        InputInterface $input, 
        OutputInterface $output
        ): int
    {
        AppSettings::load();
        $templateName = $input->getArgument('template_name');
        $TemplateController = new TemplateController(new TemplateModel($templateName));
        $TemplateController->createTemplate();
        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setHelp('This command allows you to create a template in your theme.')
             ->addArgument('template_name', InputArgument::REQUIRED, 'What is the name of this template (no spaces please)?');
    }
}
