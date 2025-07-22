<?php 

namespace Kenjiefx\ScratchPHP\App\Generators;

use Kenjiefx\ScratchPHP\App\Interfaces\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Interfaces\ThemeServiceInterface;
use Kenjiefx\ScratchPHP\App\Themes\ThemeModel;
use Kenjiefx\ScratchPHP\App\Utils\DirectoryService;
use Symfony\Component\Filesystem\Filesystem;


class ThemeGenerator {

    public function __construct(
        private ConfigurationInterface $configuration,
        private ThemeServiceInterface $themeService,
        private DirectoryService $directoryService,
        private Filesystem $filesystem,
    ) {}

    public function generate(
        string $name
    ) {
        $themeModel = new ThemeModel($name);
        $themeDir = $this->themeService->getThemeDir($themeModel);
        if ($this->doesThemeExist($themeDir)) {
            throw new \Exception("Theme already created: " . $name);
        }
        // Create theme dir 
        $this->directoryService->create($themeDir);
        // Create index php
        $this->generateIndexPhp($themeDir);
        // Create the rest of the dirs
        $this->directoryService->create($this->themeService->getAssetsDir($themeModel));
        $this->directoryService->create($this->themeService->getTemplatesDir($themeModel));
        $this->directoryService->create($this->themeService->getComponentsDir($themeModel));
        $this->directoryService->create($this->themeService->getSnippetsDir($themeModel));
        $this->directoryService->create($this->themeService->getBlocksDir($themeModel));
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
                <link rel="icon" href="<?php asset('favicon.ico'); ?>" type="image/x-icon">
                <title><?php page_title(); ?></title>
                <?php template_assets(); ?>
            </head>
            <body>
                <?php template_content(); ?>
            </body>
        </html>
        PHP;
        $filePath = "{$themeDir}/index.php";
        $this->filesystem->dumpFile($filePath, $indexPhp);
    }

}