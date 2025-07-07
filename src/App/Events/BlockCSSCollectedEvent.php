<?php 

namespace Kenjiefx\ScratchPHP\App\Events;

use Kenjiefx\ScratchPHP\App\Blocks\BlockModel;

class BlockCSSCollectedEvent implements EventInterface {

    private string $name;
    private BlockModel | null $blockModel;
    private string $content;
    private string $blockDir;

    public function __construct(array $params = []) {
        $this->blockModel = $params['model'] ?? null;
        $this->blockDir = $params['dir'] ?? '';
        $this->content = $params['content'] ?? '';
        $this->name = BlockCSSCollectedEvent::class;
    }

    public function getName():string {
        return $this->name;
    }

    public function getBlock(): BlockModel {
        return $this->blockModel;
    }

    public function getContent(): string {
        return $this->content;
    }

    public function getBlockDir(): string {
        return $this->blockDir;
    }

    public function updateContent(
        string $content
    ): void {
        $this->content = $content;
    }

}