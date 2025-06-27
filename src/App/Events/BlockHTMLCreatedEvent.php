<?php 

namespace Kenjiefx\ScratchPHP\App\Events;

use Kenjiefx\ScratchPHP\App\Blocks\BlockModel;

class BlockHTMLCreatedEvent implements EventInterface {

    private string $name;
    private BlockModel | null $blockModel;
    private string $content;
    private string $blockDir;

    public function __construct(array $params = []) {
        $this->blockModel = $params['blockModel'] ?? null;
        $this->blockDir = $params['blockDir'] ?? '';
        $this->content = $params['content'] ?? '';
        $this->name = BlockHTMLCreatedEvent::class;
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