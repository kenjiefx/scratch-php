<?php

namespace Kenjiefx\ScratchPHP\App\Events;
use Kenjiefx\ScratchPHP\App\Events\EventInterface;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;

class HTMLBuildCompletedEvent implements EventInterface
{
    private string $name;

    public function __construct(
        private PageModel | null $pageModel = null,
        private string $htmlContent = ""
    ) {
        $this->name = HTMLBuildCompletedEvent::class;
    }

    public function getName():string {
        return $this->name;
    }

    public function getPage(): PageModel {
        return $this->pageModel;
    }

    public function getContent(): string {
        return $this->htmlContent;
    }

    public function updateContent(
        string $htmlContent
    ): void {
        $this->htmlContent = $htmlContent;
    }
}
