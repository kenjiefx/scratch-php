<?php
use Kenjiefx\ScratchPHP\App\Interfaces\EventInterface;

class OnBuildHtmlEvent implements EventInterface
{
    private $name;
    private $data;

    public function __construct($name, $data) {
        $this->name = $name;
        $this->data = $data;
    }

    public function getName():string {
        return $this->name;
    }

    public function getData() {
        return $this->data;
    }
}
