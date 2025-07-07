<?php 

namespace Kenjiefx\ScratchPHP\App\Templates;

use Kenjiefx\ScratchPHP\App\Directories\DirectoryService;
use Kenjiefx\ScratchPHP\App\Events\EventDispatcher;
use Kenjiefx\ScratchPHP\App\Events\TemplateCreatedEvent;
use Kenjiefx\ScratchPHP\App\Files\FileService;
use Kenjiefx\ScratchPHP\App\Themes\ThemeModel;

class TemplateGenerator {

    public function __construct(
        public readonly TemplateServiceInterface $templateService,
        public readonly FileService $fileService,
        public readonly DirectoryService $directoryService,
        public readonly EventDispatcher $eventDispatcher
    ) {}

    public function generate(
        string $name,
        ThemeModel $themeModel
    ){
        $templateModel = new TemplateModel($name);
        $templatePath = $this->templateService->getTemplatePath($themeModel, $templateModel);

        if ($this->fileService->fileExists($templatePath)) {
            throw new \Exception("Template already exists: " . $name);
        }

        $templateDir = $this->fileService->getDir($templatePath);
        if (!$this->directoryService->exists($templateDir)) {
            // create the directory if it does not exist
            $this->directoryService->create($templateDir);
        }

        $event = new TemplateCreatedEvent([
            'templateModel' => $templateModel,
            'templatePath' => $templatePath->path,
            'templateContent' => '',
        ]);
        $this->eventDispatcher->dispatchEvent($event);
        $updatedContent = $event->getContent();
        $this->fileService->writeFile($templatePath, $updatedContent);
    }

}