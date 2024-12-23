<?php

namespace Kenjiefx\ScratchPHP\App\Configuration;
use Symfony\Component\Console\Application;

class CommandsRegistry {

    static Application $ConsoleApplication;

    public static function register(string $ClassReference){
        static::$ConsoleApplication->add(new $ClassReference);
    }

    public static function console(Application $ConsoleApplication){
        static::$ConsoleApplication = $ConsoleApplication;
    }

}