<?php

namespace Kenjiefx\ScratchPHP\Extensions;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'create:something')]
class CommandExtension extends Command {
    protected static $defaultDescription = 'Test extension external command';

    protected function execute(
        InputInterface $input, 
        OutputInterface $output
        ): int
    {
        echo 'test extension external command executed';
        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setHelp('Test extension external command.');
    }
}