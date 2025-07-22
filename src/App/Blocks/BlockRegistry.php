<?php 

namespace Kenjiefx\ScratchPHP\App\Blocks;

class BlockRegistry {

    /**
     * @var BlockModel[]
     */
    private array $blocks = [];

    public function __construct(

    ) {}

    public function register(BlockModel $component): void {
        $this->blocks[] = $component;
    }

    public function getAll(): BlockIterator {
        return new BlockIterator($this->blocks);
    }

}