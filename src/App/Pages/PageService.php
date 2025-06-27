<?php 

namespace Kenjiefx\ScratchPHP\App\Pages;

use Kenjiefx\ScratchPHP\App\Configurations\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Exports\ExportModel;

class PageService {

    public const PAGES_DIR = '/pages';

    public function __construct(
        public readonly ConfigurationInterface $configuration
    ) {}

    public function getDir(): string {
        $root = $this->configuration->getRootDir();
        return $root . self::PAGES_DIR;
    }


}