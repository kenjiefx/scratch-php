<?php 

namespace Kenjiefx\ScratchPHP\App\Configurations;

/**
 * A Configuration that is based on scratch.json file.
 */
class ScratchJsonConfiguration implements ConfigurationInterface
{
    protected string $rootDir;
    protected string $exportDir;
    protected string $themeName;
    protected bool $toUseHashedFilenames = false;
    protected string | null $pageToBuild = null;
    protected string $baseUrl;

    protected array $extensions = [];

    public function __construct() {
        $this->load();
    }

    private function load(){
        $this->rootDir = ROOT;
        $scratchJsonPath = ROOT . '/scratch.json';
        if (!file_exists($scratchJsonPath)) {
            throw new \Exception("scratch.json file not found at path: " . $scratchJsonPath);
        }
        $jsonContent = file_get_contents($scratchJsonPath);
        $configs = json_decode($jsonContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Error decoding JSON: " . json_last_error_msg());
        }
        if (!isset($configs['theme'])) {
            throw new \Exception("Theme not defined in scratch.json. Please define a theme.");
        }
        if (!isset($configs['exportDir'])) {
            throw new \Exception("Export directory not defined in scratch.json. Please define an exportDir.");
        }
        $this->themeName = $configs['theme'];
        $this->exportDir = $configs['exportDir'];
        $this->baseUrl = $configs['baseUrl'] ?? '/';
        $buildConfigs = $configs['build'] ?? [];
        $this->toUseHashedFilenames = $buildConfigs['useHashedFilenames'] ?? false;
        $this->pageToBuild = $buildConfigs['pageToBuild'] ?? null;
        if (isset($configs['extensions']) && is_array($configs['extensions'])) {
            $this->extensions = $configs['extensions'];
        }
    }

    public function setBaseUrl(
        string $baseUrl
    ) : void {
        $this->baseUrl = $baseUrl;
    }
    
    public function getRootDir(): string
    {
        return $this->rootDir;
    }

    public function getExportDir(): string
    {
        return $this->exportDir;
    }

    public function getThemeName(): string
    {
        return $this->themeName;
    }

    public function useHashedFilenames(): bool
    {
        return $this->toUseHashedFilenames;
    }

    public function pageToBuild(): string
    {
        return $this->pageToBuild;
    }

    public function getExtensions(): array
    {
        return $this->extensions;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }
}