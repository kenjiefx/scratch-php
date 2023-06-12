<?php

namespace Kenjiefx\ScratchPHP\App\Commands\Create;
use Kenjiefx\ScratchPHP\App\Components\ComponentController;
use Kenjiefx\ScratchPHP\App\Configuration\AppSettings;
use Kenjiefx\ScratchPHP\App\Factory\ContainerFactory;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'create:component')]
class Component extends Command
{
    protected static $defaultDescription = 'Creates a component in your theme';

    protected function execute(
        InputInterface $input, 
        OutputInterface $output
        ): int
    {
        $component_name = $input->getArgument('component_name');
        $ComponentController = ContainerFactory::create()->get(ComponentController::class);
        $ComponentController->create_component($component_name);
        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setHelp('This command allows you to create a component in your theme.')
             ->addArgument('component_name', InputArgument::REQUIRED, 'What is the name of this component (no spaces please)?');
    }


}