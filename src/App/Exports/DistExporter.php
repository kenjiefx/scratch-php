<?php 

namespace Kenjiefx\ScratchPHP\App\Exports;

use Kenjiefx\ScratchPHP\App\Configurations\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Directories\DirectoryService;
use Kenjiefx\ScratchPHP\App\Files\FileFactory;
use Kenjiefx\ScratchPHP\App\Files\FileService;

class DistExporter implements ExporterInterface {

    public function __construct(
        public readonly ConfigurationInterface $configuration,
        public readonly FileService $fileService,
        public readonly FileFactory $fileFactory,
        public readonly DirectoryService $directoryService
    ) {}

    public const DIST_DIR = '/dist';

    public function export(ExportModel $export): void {
        $rootDir = $this->configuration->getRootDir();
        $exportDir = $rootDir.self::DIST_DIR;
        $exportPath = $exportDir . '/' . $export->relativePath;
        $exportFile = $this->fileFactory->create($exportPath);
        $exportFileDir = $this->fileService->getDir($exportFile);
        $this->directoryService->create($exportFileDir);
        $this->fileService->writeFile(
            $exportFile, $export->content
        );
    }


}