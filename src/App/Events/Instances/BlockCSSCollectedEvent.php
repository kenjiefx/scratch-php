<?php 

namespace Kenjiefx\ScratchPHP\App\Events\Instances;

use Kenjiefx\ScratchPHP\App\Blocks\BlockModel;
use Kenjiefx\ScratchPHP\App\Events\EventInterface;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;

/**
 * BlockCSSCollectedEvent is dispatched when CSS content for a block is collected.
 */
class BlockCSSCollectedEvent implements EventInterface {

    public function __construct(
        /**
         * The page associated with the block.
         * @var PageModel
         */
        public readonly PageModel $page,

        /**
         * The block for which CSS is collected.
         * @var BlockModel
         */
        public readonly BlockModel $block,

        /**
         * The collected CSS content.
         * @var string
         */
        public string $content
    ) {}

}