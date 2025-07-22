<?php 

namespace Kenjiefx\ScratchPHP\App\Implementations\PageJSON;

use Kenjiefx\ScratchPHP\App\Interfaces\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Utils\DirectoryService;
use Kenjiefx\ScratchPHP\App\Interfaces\PageCollectorInterface;
use Kenjiefx\ScratchPHP\App\Pages\PageFactory;
use Kenjiefx\ScratchPHP\App\Pages\PageIterator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;

class PageJSONCollector implements PageCollectorInterface {

    public const PAGES_DIR = 'pages';

    public function __construct(
        private ConfigurationInterface $configuration,
        private DirectoryService $directoryService,
        private Finder $finder,
        private PageFactory $pageFactory,
        private Filesystem $filesystem
    ) {}

    public function collectAll(): PageIterator {
        $pageJsonDir = $this->getDir();
        // Recursively fetches all .json files in the pages directory
        $jsonFiles = $this->finder->files()->in($pageJsonDir)->name('*.json');
        /** @var array<PageModel> */
        $pageModels = [];
        foreach ($jsonFiles as $file) {
            $pageName = $this->convertPathToPageName($file->getPathname());
            $pageJson = $this->loadPageFromJson($file->getPathname());
            $this->validateRequiredFields($pageJson);
            $pageData = $pageJson["data"] ?? [];
            $pageModels[] = $this->pageFactory->create(
                name: $pageName,
                title: $pageJson['title'],
                template: $pageJson['template'],
                theme: $this->configuration->getThemeName(),
                data: $pageData
            );
        }
        return new PageIterator($pageModels);
    }

    public function collectByName(string $name): PageIterator {
        $name = $this->normalizePageName($name);
        $filePath = $this->getDir() . '/' . $name;
        $pageName = $this->convertPathToPageName($filePath);
        $pageJson = $this->loadPageFromJson($filePath);
        $this->validateRequiredFields($pageJson);
        $pageData = $pageJson["data"] ?? [];
        $pageModel = $this->pageFactory->create(
            name: $pageName,
            title: $pageJson['title'],
            template: $pageJson['template'],
            theme: $this->configuration->getThemeName(),
            data: $pageData
        );
        return new PageIterator([$pageModel]);
    }

    public function doesExist(string $name): bool {
        $name = $this->normalizePageName($name);
        $filePath = $this->getDir() . '/' . $name;
        return $this->filesystem->exists($filePath);
    }

    /**
     * Normalizes the page name to ensure it has a consistent format.
     * It trims leading and trailing slashes and ensures it ends with '.json'.
     * @param string $name
     * @return string
     */
    public function normalizePageName(string $name) {
        $name = ltrim($name, '/');
        $name = rtrim($name, '/');
        if (!str_ends_with($name, '.json')) {
            $name .= '.json';
        }
        return $name;
    }

    public function loadPageFromJson(string $filePath) {
        if (!$this->filesystem->exists($filePath)) {
            throw new \Exception("Page json file not found: {$filePath}");
        }
        $content = $this->filesystem->readFile($filePath);
        $pageJson = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $message = "Error parsing json at this path: {$filePath}";
            throw new \Exception($message);
        }
        return $pageJson;
    }

    public function validateRequiredFields(array $pageJson) {
        $requiredFields = ['template', 'title'];
        foreach ($requiredFields as $field) {
            if (!isset($pageJson[$field])) {
                throw new \Exception("Missing required field '{$field}' in page json.");
            }
        }
    }

    /**
     * Converts a file path to a page name.
     * @param string $path
     * @return string
     */
    public function convertPathToPageName(string $path) {
        $pageJsonDir = $this->getDir();
        $pageName = str_replace([$pageJsonDir, '.json'], '', $path);
        return $this->normalizePath($pageName);
    }

    public function getDir() {
        $pageJsonDir = $this->configuration->getRootDir() . '/' . PageJSONCollector::PAGES_DIR;
        return $pageJsonDir;
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