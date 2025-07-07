<?php

namespace Kenjiefx\ScratchPHP\App\Blocks;

use Kenjiefx\ScratchPHP\App\Blocks\BlockModel;
use Kenjiefx\ScratchPHP\App\Blocks\BlocksIterator;

/**
 * Registers and manages the blocks used within the template.
 * This object holds an array of
 */
class BlockRegistry {

    /**
     * An array of Block Models
     */
    private static array $registry = [];

    public function __construct(

    ) {}

    public function register(BlockModel $blockModel){
        array_push(self::$registry, $blockModel);
    }

    public function get(): BlocksIterator {
        return new BlocksIterator(self::$registry);
    }

    public function clear(){
        self::$registry = [];
    }

}