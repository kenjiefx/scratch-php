<?php

namespace Kenjiefx\ScratchPHP\App\Interfaces;

interface EventInterface {
    public function __construct($name, $data);
    public function getName():string;
    public function getData():mixed;
}
