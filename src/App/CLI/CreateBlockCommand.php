<?php

namespace Kenjiefx\ScratchPHP\App\CLI;
use Kenjiefx\ScratchPHP\App\Generators\BlockGenerator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'create:block')]
class CreateBlockCommand extends Command
{
    protected static $defaultDescription = 'Creates a block in your theme';

    public function __construct(
        private BlockGenerator $blockGenerator
    ) {
        parent::__construct();
    }

    protected function execute(
        InputInterface $input, 
        OutputInterface $output
        ): int
    {
        $blockName = $input->getArgument('block_name');
        $this->blockGenerator->generate($blockName);
        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setHelp('This command allows you to create a block in your theme.')
             ->addArgument('block_name', InputArgument::REQUIRED, 'What is the name of this block (no spaces please)?')
             ->addOption('clean',null,InputOption::VALUE_NONE,'Create block without modification from your extensions?');
    }


}