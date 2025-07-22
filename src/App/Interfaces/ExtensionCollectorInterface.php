<?php 

namespace Kenjiefx\ScratchPHP\App\Interfaces;

use Kenjiefx\ScratchPHP\App\Extensions\ExtensionIterator; 

interface ExtensionCollectorInterface {
    
    public function collect(): ExtensionIterator;

}