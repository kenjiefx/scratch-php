<?php

namespace Kenjiefx\ScratchPHP\App\CLI;
use Kenjiefx\ScratchPHP\App\Blocks\BlockGenerator;
use Kenjiefx\ScratchPHP\App\Configurations\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Themes\ThemeModel;
use Kenjiefx\ScratchPHP\Container;
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

    protected function execute(
        InputInterface $input, 
        OutputInterface $output
        ): int
    {
        $configuration = Container::get()->get(ConfigurationInterface::class);
        $themeName = $configuration->getThemeName();
        $themeModel = new ThemeModel($themeName);
        $blockName = $input->getArgument('block_name');
        $blockGenerator = Container::get()->get(BlockGenerator::class);
        $blockGenerator->generate(
            $blockName, $themeModel
        );
        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setHelp('This command allows you to create a block in your theme.')
             ->addArgument('block_name', InputArgument::REQUIRED, 'What is the name of this block (no spaces please)?')
             ->addOption('clean',null,InputOption::VALUE_NONE,'Create block without modification from your extensions?');
    }


}