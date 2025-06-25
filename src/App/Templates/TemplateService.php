<?php 

namespace Kenjiefx\ScratchPHP\App\Templates;

use Kenjiefx\ScratchPHP\App\Configurations\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Files\File;
use Kenjiefx\ScratchPHP\App\Files\FileFactory;
use Kenjiefx\ScratchPHP\App\Themes\ThemeModel;
use Kenjiefx\ScratchPHP\App\Themes\ThemeService;

class TemplateService implements TemplateServiceInterface {

    public function __construct(
        public readonly ConfigurationInterface $configuration,
        public readonly ThemeService $themeService,
        public readonly FileFactory $fileFactory
    ) {}

    public const TEMPLATE_DIR = '/templates';

    public function getTemplatesDir(
        ThemeModel $themeModel
    ): string {
        $themeDir = $this->themeService->getThemeDir($themeModel);
        return $themeDir . self::TEMPLATE_DIR;
    }

    /**
     * Returns the path to a specific template file.
     * @param \Kenjiefx\ScratchPHP\App\Themes\ThemeModel $themeModel
     * @param \Kenjiefx\ScratchPHP\App\Templates\TemplateModel $templateModel
     * @return string
     */
    public function getTemplatePath(
        ThemeModel $themeModel,
        TemplateModel $templateModel
    ): File {
        $templateDir = $this->getTemplatesDir($themeModel);
        $templateName = $templateModel->name;
        $templatePath = $templateDir . '/' . $templateName . '.php';
        return $this->fileFactory->create($templatePath);
    }

}