<?php

namespace Kenjiefx\ScratchPHP\App\CLI;
use Kenjiefx\ScratchPHP\App\Generators\ComponentGenerator;
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

    public function __construct(
        private ComponentGenerator $componentGenerator,
    ) {
        parent::__construct();
    }

    protected function execute(
        InputInterface $input, 
        OutputInterface $output
        ): int
    {
        $componentName = $input->getArgument('component_name');
        $this->componentGenerator->generate($componentName);
        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setHelp('This command allows you to create a component in your theme.')
             ->addArgument('component_name', InputArgument::REQUIRED, 'What is the name of this component (no spaces please)?')
             ->addOption('clean',null,InputOption::VALUE_NONE,'Create component without modification from your extensions?');
    }


}