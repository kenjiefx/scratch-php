<?php 

namespace Kenjiefx\ScratchPHP\App\Blocks;

class BlockFactory {

    private static int $idIncrementor = 1;

    /**
     * Creates a new BlockModel instance.
     *
     * @param string $name The name of the block.
     * @return BlockModel The created BlockModel instance.
     */
    public static function create(string $name): BlockModel {
        $componentId = strval(self::$idIncrementor++).uniqid();
        return new BlockModel(
            $name,
            $componentId
        );
    }
}