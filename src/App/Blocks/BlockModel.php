<?php 

namespace Kenjiefx\ScratchPHP\App\Blocks;

/**
 * Represents a block in the application.
 */
class BlockModel {

    public function __construct(
        /**
         * The unique identifier for the block.
         * @var string
         */
        public readonly string $id,

        /**
         * The name of the block.
         * This typically contains the namespace and the name
         * @example "ExampleBlocks/BlockName"
         * @var string
         */
        public readonly string $name,

        /**
         * Data associated to the block.
         */
        public readonly BlockData $data
    ) {}

}