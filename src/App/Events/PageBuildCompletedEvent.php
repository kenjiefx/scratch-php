<?php 

namespace Kenjiefx\ScratchPHP\App\Events;

use Kenjiefx\ScratchPHP\App\Pages\PageModel;

class PageBuildCompletedEvent implements EventInterface {


    private string $name;

    public function __construct(
        private PageModel $pageModel,
    ) {
        $this->name = PageBuildCompletedEvent::class;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getPageModel(): PageModel {
        return $this->pageModel;
    }

}