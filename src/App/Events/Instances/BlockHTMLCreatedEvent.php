<?php 

namespace Kenjiefx\ScratchPHP\App\Events\Instances;

use Kenjiefx\ScratchPHP\App\Blocks\BlockModel;
use Kenjiefx\ScratchPHP\App\Events\EventInterface;

/**
 * Event triggered when a block's HTML is created.
 */
class BlockHTMLCreatedEvent implements EventInterface {

    public function __construct(
        /**
         * The block model associated with the event.
         * @var BlockModel
         */
        public readonly BlockModel $block,

        /**
         * The HTML content generated for the block.
         * @var string
         */
        public string $content
    ) {}

}