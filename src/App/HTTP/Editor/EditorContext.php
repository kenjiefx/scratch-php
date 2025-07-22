<?php 

namespace Kenjiefx\ScratchPHP\App\HTTP\Editor;

class EditorContext {

    private static array $data = [];

    public static function set(string $key, mixed $value): void {
        static::$data[$key] = $value;
    }

    public static function get(string $key): mixed {
        return static::$data[$key] ?? null;
    }

}