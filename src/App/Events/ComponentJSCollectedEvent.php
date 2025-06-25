<?php

namespace Kenjiefx\ScratchPHP\App\Events;
use Kenjiefx\ScratchPHP\App\Components\ComponentModel;
use Kenjiefx\ScratchPHP\App\Events\EventInterface;

/**
 * This event is triggered when the Javascript for a component is collected.
 * It can be used to handle or modify the Javascript code before it is processed further.
 */
class ComponentJSCollectedEvent implements EventInterface
{
    private string $name;
    private ComponentModel $componentModel;
    private string $content;

    public function __construct(
        ComponentModel $componentModel, 
        string $content
    ) {
        $this->componentModel = $componentModel;
        $this->content = $content;
        $this->name = ComponentJSCollectedEvent::class;
    }

    public function getName():string {
        return $this->name;
    }

    public function getComponent(): ComponentModel {
        return $this->componentModel;
    }

    public function getContent(): string {
        return $this->content;
    }

    public function updateContent(
        string $content
    ): void {
        $this->content = $content;
    }
}
