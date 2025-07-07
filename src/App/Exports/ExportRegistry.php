<?php 

namespace Kenjiefx\ScratchPHP\App\Exports;

/**
 * ExportRegistry class manages a collection of ExportModel instances.
 * This registry can be used to store and iterate over multiple exports.
 */
class ExportRegistry implements \Iterator {
    private array $exports = [];
    private int $position = 0;

    public function addExport(ExportModel $export): void {
        $this->exports[] = $export;
    }

    public function current(): ExportModel {
        return $this->exports[$this->position];
    }

    public function key(): int {
        return $this->position;
    }

    public function next(): void {
        ++$this->position;
    }

    public function rewind(): void {
        $this->position = 0;
    }

    public function valid(): bool {
        return isset($this->exports[$this->position]);
    }
}