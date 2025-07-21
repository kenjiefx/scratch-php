<?php 

namespace Kenjiefx\ScratchPHP\App\Extensions;

class ExtensionSettings implements \ArrayAccess {

    /**
     * @var array<string, mixed>
     */
    private array $settings = [];

    /**
     * @param array<string, mixed> $settings
     */
    public function __construct(array $settings = []) {
        $this->settings = $settings;
    }

    public function offsetExists($offset): bool {
        return isset($this->settings[$offset]);
    }

    public function offsetGet($offset): mixed {
        return $this->settings[$offset] ?? null;
    }

    public function offsetSet($offset, $value): void {
        if ($offset === null) {
            throw new \InvalidArgumentException('Offset cannot be null');
        }
        $this->settings[$offset] = $value;
    }

    public function offsetUnset($offset): void {
        unset($this->settings[$offset]);
    }

}