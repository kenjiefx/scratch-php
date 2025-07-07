<?php

namespace Kenjiefx\ScratchPHP\App\Events;
use Kenjiefx\ScratchPHP\App\Components\ComponentModel;
use Kenjiefx\ScratchPHP\App\Events\EventInterface;

class ComponentHTMLCreatedEvent implements EventInterface
{
    private $name;
    private $data;

    public function __construct(
        private ComponentModel | null $componentModel = null,
        private string $componentDir = '',
        private string $content = ''
    ) {
        $this->name = ComponentHTMLCreatedEvent::class;
    }

    public function getName():string {
        return $this->name;
    }

    public function getData():mixed {
        return $this->data;
    }

    public function getComponentModel(): ComponentModel {
        return $this->componentModel;
    }

    public function getComponentDir(): string {
        return $this->componentDir;
    }

    public function getContent(): string {
        return $this->content;
    }

    public function updateContent(string $content): void {
        $this->content = $content;
    }
}
