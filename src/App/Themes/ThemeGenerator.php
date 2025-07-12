<?php 

namespace Kenjiefx\ScratchPHP\App\Themes;


use Kenjiefx\ScratchPHP\App\Configurations\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Directories\DirectoryService;
use Kenjiefx\ScratchPHP\App\Files\FileFactory;
use Kenjiefx\ScratchPHP\App\Files\FileService;

class ThemeGenerator {

    public function __construct(
        public readonly ConfigurationInterface $configuration,
        public readonly ThemeFactory $themeFactory,
        public readonly ThemeService $themeService,
        public readonly DirectoryService $directoryService,
        public readonly FileFactory $fileFactory,
        public readonly FileService $fileService
    ) {}

    public function generate(
        string $name
    ) {
        $themeModel = $this->getThemeModel($name);
        $themeDir = $this->themeService->getThemeDir($themeModel);
        if ($this->doesThemeExist($themeDir)) {
            throw new \Exception("Theme already created: " . $name);
        }
        // Create theme dir 
        $this->directoryService->create($themeDir);
        // Create index php
        $this->generateIndexPhp($themeDir);
        // Create the rest of the dirs
        $this->directoryService->create("{$themeDir}/assets");
        $this->directoryService->create("{$themeDir}/blocks");
        $this->directoryService->create("{$themeDir}/components");
        $this->directoryService->create("{$themeDir}/snippets");
        $this->directoryService->create("{$themeDir}/templates");
    }

    public function getThemeModel(
        string $name
    ): ThemeModel {
        return $this->themeFactory->create($name);
    }

    public function doesThemeExist(string $themeDir) {
        return $this->directoryService->exists($themeDir);
    }

    public function generateIndexPhp(string $themeDir) {
        $indexPhp = <<<PHP
        <!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title><?php page_title(); ?></title>
                <?php template_assets(); ?>
            </head>
            <body>
                <?php template_content(); ?>
            </body>
        </html>
        PHP;
        $filePath = "{$themeDir}/index.php";
        $fileObject = $this->fileFactory->create($filePath);
        $this->fileService->writeFile($fileObject, $indexPhp);
    }

}