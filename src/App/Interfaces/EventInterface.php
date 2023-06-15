<?php

namespace Kenjiefx\ScratchPHP\App\Interfaces;

interface EventInterface {
    public function __construct();
    public function getName():string;
    public function getData():mixed;
}
