<?php

namespace Kenjiefx\ScratchPHP\App\Generators;

use Kenjiefx\ScratchPHP\App\Events\EventBus;
use Kenjiefx\ScratchPHP\App\Events\Instances\TemplateHTMLCreatedEvent;
use Kenjiefx\ScratchPHP\App\Interfaces\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Interfaces\ThemeServiceInterface;
use Kenjiefx\ScratchPHP\App\Templates\TemplateModel;
use Kenjiefx\ScratchPHP\App\Themes\ThemeModel;
use Kenjiefx\ScratchPHP\App\Utils\DirectoryService;
use Symfony\Component\Filesystem\Filesystem;

class TemplateGenerator {

    public function __construct(
        private ConfigurationInterface $configuration,
        private ThemeServiceInterface $themeService,
        private Filesystem $filesystem,
        private DirectoryService $directoryService,
        private EventBus $eventBus
    ) {}

    public function generate(string $name) {
        $themeName = $this->configuration->getThemeName();
        $themeModel = new ThemeModel($themeName);
        $templateModel = new TemplateModel($name);
        $templatePath = $this->themeService->getTemplatePath(
            $themeModel, $templateModel
        );
        if ($this->filesystem->exists($templatePath)) {
            throw new \Exception("Template already exists: " . $name);
        }
        $templateDir = dirname($templatePath);
        if (!$this->directoryService->exists($templateDir)) {
            $this->directoryService->create($templateDir);
        }
        $event = new TemplateHTMLCreatedEvent(
            $templateModel, ""
        );
        $this->eventBus->dispatchEvent($event);
        $this->filesystem->dumpFile(
            $templatePath, $event->content
        );
    }

}