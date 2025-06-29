<?php

namespace Kenjiefx\ScratchPHP\App\Events;
use Kenjiefx\ScratchPHP\App\Events\EventInterface;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;

class CSSBuildCompletedEvent implements EventInterface
{
    private $name;

    public function __construct(
        private PageModel | null $pageModel = null,
        private string $cssContent = ""
    ) {
        $this->name = CSSBuildCompletedEvent::class;
    }

    public function getName():string {
        return $this->name;
    }

    public function getPage(): PageModel {
        return $this->pageModel;
    }

    public function getContent(): string {
        return $this->cssContent;
    }

    public function updateContent(
        string $cssContent
    ): void {
        $this->cssContent = $cssContent;
    }
}
