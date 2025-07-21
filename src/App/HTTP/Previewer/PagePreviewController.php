<?php 

namespace Kenjiefx\ScratchPHP\App\HTTP\Previewer;
use Kenjiefx\ScratchPHP\App\HTTP\Exporter\HttpResourceExportService;
use Kenjiefx\ScratchPHP\App\Interfaces\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Interfaces\PageCollectorInterface;
use Kenjiefx\ScratchPHP\App\Orchestrators\BuildOrchestrator;
use Kenjiefx\ScratchPHP\App\Orchestrators\ExtensionOrchestrator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Symfony\Component\Filesystem\Filesystem;

class PagePreviewController {

    public function __construct(
        private PageCollectorInterface $pageCollector,
        private ConfigurationInterface $configuration,
        private BuildOrchestrator $buildOrchestrator,
        private ExtensionOrchestrator $extensionOrchestrator,
        private Filesystem $filesystem,
        private HttpResourceExportService $exportService
    ) {}

    public function page(Request $request, Response $response) {
        $this->configuration->loadConfig();
        $this->extensionOrchestrator->mount();
        $pageName = $this->getPageName($request);
        $pageIterator = $this->pageCollector->collectByName($pageName);
        $this->buildOrchestrator->build($pageIterator);
        $exportsDir = $this->exportService->getExportsDir();
        $response->getBody()->write(
            $this->filesystem->readFile("{$exportsDir}/page.html")
        );
    }

    public function assets(Request $request, Response $response) {
        $assetFile = $request->getAttribute('params');
        $exportsDir = $this->exportService->getExportsDir();
        if ($assetFile === "page.css") {
            $filePath = "{$exportsDir}/page.css";
            $response->getBody()->write($this->filesystem->readFile($filePath));
            return $response->withHeader('Content-Type', 'text/css')->withStatus(200);
        }
        if ($assetFile === "page.js") {
            $filePath = "{$exportsDir}/page.js";
            $response->getBody()->write($this->filesystem->readFile($filePath));
            return $response->withHeader('Content-Type', 'application/javascript')->withStatus(200);
        }
        $assetPath = "{$exportsDir}/{$assetFile}";
        if (!$this->filesystem->exists($assetPath)) {
            $response->getBody()->write("Assets not found");
            return $response->withHeader('Content-Type', 'text/plain')->withStatus(404);
        }
        $mimeType = mime_content_type($assetPath);
        $response->getBody()->write($this->filesystem->readFile($assetPath));
        return $response->withHeader('Content-Type', $mimeType)->withStatus(200);
    }

    /**
     * Get the page name from the request.
     * If the page does not exist, it returns 
     * '404' if a 404 page exists.
     *
     * @param Request $request
     * @return string - The name of the page to be displayed.
     * @throws \Exception - if the page does 
     * not exist and no 404 page is available.
     */
    public function getPageName(Request $request): string {
        $route = $request->getAttribute('routes');
        if ($route === "") $route = "index.html";
        $pageName = str_replace('.html', '', $route);
        if (!$this->pageCollector->doesExist($pageName)) {
            if (!$this->pageCollector->doesExist('404')) {
                throw new \Exception('Page not found and no 404 page exists.');
            }
            return '404';
        }
        return $pageName;
    }

}