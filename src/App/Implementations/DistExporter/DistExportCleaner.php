<?php

namespace Kenjiefx\ScratchPHP\App\Implementations\DistExporter;

use Kenjiefx\ScratchPHP\App\Interfaces\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Utils\DirectoryService;
use Symfony\Component\Filesystem\Filesystem;

class DistExportCleaner {

    public function __construct(
        private ConfigurationInterface $configuration,
        private Filesystem $filesystem,
        private DirectoryService $directoryService
    ) {}

    public function clearExportDir(): void {
        $rootDir = $this->configuration->getRootDir();
        $distPath = $this->configuration->getExportDir();
        $exportDir = $this->normalizePath($rootDir . '/' . $distPath);
        $this->deleteDirRecursive($exportDir);
    }

    /**
     * Recursively delete a directory and its contents.
     *
     * @param string $dir The directory to delete.
     * @param bool $isFirst Whether this is the first call (used to avoid deleting the root directory).
     * @return void
     */
    public function deleteDirRecursive($dir, $isFirst = true): void {
        if (!$this->filesystem->exists($dir)) {
            return;
        }
        if (!$this->directoryService->isDirectory($dir)) {
            $this->filesystem->remove($dir);
            return;
        }
        $items = $this->directoryService->listFiles($dir);
        foreach ($items as $item) {
            $itemPath = $dir . '/' . $item;
            if ($this->directoryService->isDirectory($itemPath)) {
                $this->deleteDirRecursive($itemPath, false);
            } else {
                $this->filesystem->remove($itemPath);
            }
        }
        if (!$isFirst) {
            $this->directoryService->delete($dir);
            return;
        }
    }

    public function normalizePath($path) {
        // Replace backslashes with slashes for consistency
        $path = str_replace('\\', '/', $path);
        // Replace multiple slashes with a single slash
        $path = preg_replace('#/+#', '/', $path);
        // Convert back to system's directory separator if needed
        return rtrim($path, '/');
    }



}