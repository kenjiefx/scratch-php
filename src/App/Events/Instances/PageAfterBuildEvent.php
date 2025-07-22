<?php 

namespace Kenjiefx\ScratchPHP\App\Events\Instances;

use Kenjiefx\ScratchPHP\App\Events\EventInterface;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;

/**
 * This event is triggered by the `BuildOrchestrator` after the build process of a page completes.
 */
class PageAfterBuildEvent implements EventInterface {

    public function __construct(
        /**
         * The page model that was built.
         * @var PageModel
         */
        public readonly PageModel $page
    ) {}

}