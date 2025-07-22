<?php 

namespace Kenjiefx\ScratchPHP\App\Events\Instances;

use Kenjiefx\ScratchPHP\App\Blocks\BlockModel;
use Kenjiefx\ScratchPHP\App\Events\EventInterface;

/**
 * Event triggered when a block's JavaScript is created.
 */
class BlockJSCreatedEvent implements EventInterface {

    public function __construct(
        /**
         * The block model associated with the event.
         * @var BlockModel
         */
        public readonly BlockModel $block,

        /**
         * The JavaScript content generated for the block.
         * @var string
         */
        public string $content
    ) {}

}