<?php 

namespace Kenjiefx\ScratchPHP\App\Events\Instances;

use Kenjiefx\ScratchPHP\App\Events\EventInterface;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;

/**
 * This event is triggered by the `BuildOrchestrator` before the build process of a page starts.
 */
class PageBeforeBuildEvent implements EventInterface {

    public function __construct(
        /**
         * The page model that will be built.
         * @var PageModel
         */
        public readonly PageModel $page
    ) {}

}