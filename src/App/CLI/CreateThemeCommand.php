<?php 

namespace Kenjiefx\ScratchPHP\App\CLI;

use Kenjiefx\ScratchPHP\App\Generators\ThemeGenerator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface; 

#[AsCommand(name: 'create:theme')]
class CreateThemeCommand extends Command {

    protected static $defaultDescription = 'Creates a new theme';

    public function __construct(
        private ThemeGenerator $themeGenerator
    ) {
        parent::__construct();
    }

    protected function execute(
        InputInterface $input, 
        OutputInterface $output
        ): int
    {
        $themeName = $input->getArgument('theme_name');
        $this->themeGenerator->generate($themeName);
        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setHelp('This command allows you to create a new theme.')
             ->addArgument('theme_name', InputArgument::REQUIRED, 'What is the name of this theme (no spaces please)?');
    }

}