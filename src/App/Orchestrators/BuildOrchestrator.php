<?php 

namespace Kenjiefx\ScratchPHP\App\Orchestrators;

use Kenjiefx\ScratchPHP\App\Interfaces\BuildServiceInterface;
use Kenjiefx\ScratchPHP\App\Pages\PageIterator;

class BuildOrchestrator {

    public function __construct(
        private readonly BuildServiceInterface $buildService
    ) {}

    public function build(PageIterator $pages) {
        foreach ($pages as $page) {
            $output = $this->buildService->buildPage($page);
            
        }
    }

}