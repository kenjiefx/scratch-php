<?php

namespace Kenjiefx\ScratchPHP\App\Events;
use Kenjiefx\ScratchPHP\App\Events\EventInterface;
use Kenjiefx\ScratchPHP\App\Templates\TemplateModel;

class TemplateCreatedEvent implements EventInterface
{
    private $name;
    private $data;
    private TemplateModel | null $templateModel;
    private string $templatePath;
    private string $templateContent;

    public function __construct(array $data = []) {
        $this->templateModel = $data['templateModel'] ?? null;
        $this->templatePath = $data['templatePath'] ?? '';
        $this->templateContent = $data['templateContent'] ?? '';
        $this->name = TemplateCreatedEvent::class;
    }

    public function getName():string {
        return $this->name;
    }

    public function getData():mixed {
        return $this->data;
    }

    public function getTemplateModel(): TemplateModel {
        return $this->templateModel;
    }

    public function getTemplatePath(): string {
        return $this->templatePath;
    }

    public function getContent(): string {
        return $this->templateContent;
    }

    public function updateContent(string $content): void {
        $this->templateContent = $content;
    }
}
