<?php 

namespace Kenjiefx\ScratchPHP\App\HTTP\Editor;
use Kenjiefx\ScratchPHP\App\Extensions\ExtensionModel;
use Kenjiefx\ScratchPHP\App\Extensions\ExtensionSettings;
use Kenjiefx\ScratchPHP\App\HTTP\Editor\Extension\EditorExtension;
use Kenjiefx\ScratchPHP\App\HTTP\Exporter\HttpResourceExportService;
use Kenjiefx\ScratchPHP\App\HTTP\Previewer\PagePreviewController;
use Kenjiefx\ScratchPHP\App\Interfaces\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Interfaces\PageCollectorInterface;
use Kenjiefx\ScratchPHP\App\Orchestrators\BuildOrchestrator;
use Kenjiefx\ScratchPHP\App\Orchestrators\ExtensionOrchestrator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Symfony\Component\Filesystem\Filesystem;

class EditorController extends PagePreviewController {

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
        $this->registerEditorExtension();
        $component = $this->getComponentNameFromQueryParams($request);
        EditorContext::set('component:name', $component);
        $pageIterator = $this->pageCollector->collectAll();
        $this->buildOrchestrator->build($pageIterator);
        $exportsDir = $this->exportService->getExportsDir();
        $response->getBody()->write(
            $this->filesystem->readFile("{$exportsDir}/page.html")
        );
    }

    public function editorAssets(Request $request, Response $response) {
        $assetFile = $request->getAttribute("params");
        if ($assetFile === "editor.css") {
            $content = $this->filesystem->readFile( __DIR__ . "/theme/editor.css");
            $response->getBody()->write($content);
            return $response->withHeader('Content-Type', 'text/css')->withStatus(200);
        }
        if ($assetFile === "editor.js") {
            $content = $this->filesystem->readFile(__DIR__ . "/theme/editor.js");
            $response->getBody()->write($content);
            return $response->withHeader('Content-Type', 'application/javascript')->withStatus(200);
        }
        throw new \Exception("Unsupported editor asset");
    }

    public function registerEditorExtension() {
        $extensionModel = new ExtensionModel(
            fullyQualifiedName: EditorExtension::class,
            settings: new ExtensionSettings()
        );
        $this->extensionOrchestrator->register($extensionModel);
    }

    public function getComponentNameFromQueryParams(Request $request): ?string {
        $queryParams = $request->getQueryParams();
        $component = $queryParams['component'] ?? null;
        if ($component === null) {
            throw new \Exception("Component parameter is required for rendering the editor.");
        }
        return str_replace('.', '/', $component);
    }

}