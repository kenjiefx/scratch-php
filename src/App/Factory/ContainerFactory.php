<?php

declare(strict_types=1);
namespace Kenjiefx\ScratchPHP\App\Factory;
use League\Container\Container as Container;

class ContainerFactory {

    private static $instance;

    public static function create()
    {
        if (!isset(static::$instance)) {
            static::$instance = new Container;
        }
        return static::$instance;
    }

}