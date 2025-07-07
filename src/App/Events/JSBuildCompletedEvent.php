<?php

namespace Kenjiefx\ScratchPHP\App\Events;
use Kenjiefx\ScratchPHP\App\Events\EventInterface;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;

class JSBuildCompletedEvent implements EventInterface
{
    private $name;

    public function __construct(
        private PageModel | null $pageModel = null,
        private string $jsContent = ""
    ) {
        $this->name = JSBuildCompletedEvent::class;
    }

    public function getName():string {
        return $this->name;
    }

    public function getPage(): PageModel {
        return $this->pageModel;
    }

    public function getContent(): string {
        return $this->jsContent;
    }

    public function updateContent(
        string $jsContent
    ): void {
        $this->jsContent = $jsContent;
    }
}
