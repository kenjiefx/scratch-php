<?php 

namespace Kenjiefx\ScratchPHP\App\Statics;

class StaticAssetsRegistry {

    private static array $registry = [];

    public function __construct() {}

    public function register(StaticAssetsModel $staticAssetsModel) {
        $fileName = $staticAssetsModel->fileName;
        if (isset($registry[$fileName])) {
            return; // Already registered
        }
        self::$registry[$fileName] = $staticAssetsModel;
    }

    public function clear() {
        self::$registry = [];
    }

    public function getAll(): StaticAssetsIterator {
        $models = [];
        foreach (self::$registry as $fileName => $staticAssetsModel) {
            $models[] = $staticAssetsModel;
        }
        return new StaticAssetsIterator($models);
    }



}