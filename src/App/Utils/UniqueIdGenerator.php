<?php

namespace Kenjiefx\ScratchPHP\App\Utils;

class UniqueIdGenerator {

    private static int $idIncrementor = 1;
    private static array $chars = ["a", "b", "c", "d", "e", "f"];

    public function __construct(
        
    ) {}

    public function generate() {
        $id = strval(self::$idIncrementor++);
        $randomChar = self::$chars[array_rand(self::$chars)];
        $uniqueId = $randomChar . uniqid() . $id;
        return $uniqueId;
    }

}