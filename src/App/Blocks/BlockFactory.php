<?php 

namespace Kenjiefx\ScratchPHP\App\Blocks;

use Kenjiefx\ScratchPHP\App\Utils\UniqueIdGenerator;

class BlockFactory {

    public function __construct(
        private UniqueIdGenerator $uniqueIdGenerator
    ) {}

    /**
     * Creates a new BlockModel instance.
     *
     * @param string $name The name of the block.
     * @param array $data The data associated with the block.
     * @return BlockModel The created BlockModel instance.
     */
    public function create(string $name, array $data): BlockModel {
        $uniqueId = $this->uniqueIdGenerator->generate();
        $blockData = new BlockData();
        foreach ($data as $key => $value) {
            $blockData[$key] = $value;
        }
        return new BlockModel(
            id: $uniqueId,
            name: $name,
            data: $blockData
        );
    }

}