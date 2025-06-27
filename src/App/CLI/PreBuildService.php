<?php 

namespace Kenjiefx\ScratchPHP\App\CLI;

use Kenjiefx\ScratchPHP\App\Configurations\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Directories\DirectoryService;
use Kenjiefx\ScratchPHP\App\Files\FileFactory;
use Kenjiefx\ScratchPHP\App\Files\FileService;
use Kenjiefx\ScratchPHP\App\Pages\PageFactory;
use Kenjiefx\ScratchPHP\App\Pages\PageRegistry;
use Kenjiefx\ScratchPHP\App\Pages\PageService;

class PreBuildService {

    private static array $pages = [];

    public function __construct(
        public readonly PageService $pageService,
        public readonly PageFactory $pageFactory,
        public readonly DirectoryService $directoryService,
        public readonly ConfigurationInterface $configuration,
        public readonly FileService $fileService,
        public readonly FileFactory $fileFactory
    ) {}


    public function createPageRegistry(array $options): PageRegistry {
        $pagesDir = $this->pageService->getDir();
        if ($options['pagePath'] !== null) {
            $pageJsonPath = "{$pagesDir}/{$options['pagePath']}";
            $pageModel    = $this->pageFactory->create($pageJsonPath);
            return new PageRegistry([$pageModel]);
        }
        $this->clearExportDir();
        $this->discoverPagesRecursive($pagesDir);
        return new PageRegistry(static::$pages);
    }

    public function discoverPagesRecursive(string $dirpath){
        $filenames = $this->directoryService->listFiles($dirpath);
        foreach ($filenames as $filename) {
            $filepath = $dirpath . '/' . $filename;
            if ($this->directoryService->isDirectory($filepath)) { 
                # Recursively discover pages
                $this->discoverPagesRecursive($filepath);
            } else {
                static::$pages[] = $this->pageFactory->create($filepath);
            }
        }
    }

    public function clearExportDir(){
        $rootDir   = $this->configuration->getRootDir();
        $distPath  = $this->configuration->getExportDir();
        $exportDir = $rootDir . '/' . $distPath;
        $this->deleteDirRecursive($exportDir);
    }

    public function deleteDirRecursive($dir, $isFirst = true) {

        // Check if the directory exists in the filesystem
        $file = $this->fileFactory->create($dir);
        if (!$this->fileService->fileExists($file)) {
            return false;
        }

        // Check if the directory exists
        if (!$this->directoryService->isDirectory($dir)) {
            // If the directory does not exist, we can use the file service to delete it
            return $this->fileService->deleteFile($dir); 
        }
    
        $items = $this->directoryService->listFiles($dir);
        foreach ($items as $item) {
            $itemPath = $dir . '/' . $item;
            if ($this->directoryService->isDirectory($itemPath)) {
                // If it's a directory, recursively delete its contents
                $this->deleteDirRecursive($itemPath, false);
            } else {
                // If it's a file, delete it
                $file = $this->fileFactory->create($itemPath);
                $this->fileService->deleteFile($file);
            }
        }

        // Finally, delete the directory itself
        if (!$isFirst) {
            return $this->directoryService->delete($dir);
        }
    
    }
    

}