<?php 

namespace Kenjiefx\ScratchPHP\App\Blocks;

class BlockData implements \ArrayAccess {

    private $data = [];

    public function __construct(array $data = []) {
        $this->data = $data;
    }

    public function offsetExists($offset): bool {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset): mixed {
        return $this->data[$offset] ?? null;
    }

    public function offsetSet($offset, $value): void {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetUnset($offset): void {
        unset($this->data[$offset]);
    }

}