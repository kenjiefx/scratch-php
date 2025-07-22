<?php 

namespace Kenjiefx\ScratchPHP\App\Events\Instances;

use Kenjiefx\ScratchPHP\App\Blocks\BlockModel;
use Kenjiefx\ScratchPHP\App\Events\EventInterface;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;

/**
 * BlockJSCollectedEvent is dispatched when JavaScript content for a block is collected.
 */
class BlockJSCollectedEvent implements EventInterface {

    public function __construct(
        /**
         * The page associated with the block.
         * @var PageModel
         */
        public readonly PageModel $page,

        /**
         * The block for which JavaScript is collected.
         * @var BlockModel
         */
        public readonly BlockModel $block,

        /**
         * The collected JavaScript content.
         * @var string
         */
        public string $content
    ) {}

}