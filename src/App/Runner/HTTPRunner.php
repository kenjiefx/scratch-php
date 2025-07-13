<?php 

namespace Kenjiefx\ScratchPHP\App\Runner;
use Kenjiefx\ScratchPHP\App\Configurations\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Configurations\ScratchJsonConfiguration;
use Kenjiefx\ScratchPHP\App\Exports\ExporterInterface;
use Kenjiefx\ScratchPHP\App\Extensions\ScratchJsonExtManager;
use Kenjiefx\ScratchPHP\App\Files\FileFactory;
use Kenjiefx\ScratchPHP\App\HTTP\Editor\EditorController;
use Kenjiefx\ScratchPHP\App\HTTP\Editor\EditorExportService;
use Kenjiefx\ScratchPHP\App\HTTP\Editor\EditorExtension;
use Kenjiefx\ScratchPHP\App\HTTP\Editor\EditorTemplateService;
use Kenjiefx\ScratchPHP\App\HTTP\Previewer\PagePreviewController;
use Kenjiefx\ScratchPHP\App\HTTP\Previewer\PagePreviewerExportService;
use Kenjiefx\ScratchPHP\App\Templates\TemplateService;
use Kenjiefx\ScratchPHP\App\Templates\TemplateServiceInterface;
use Kenjiefx\ScratchPHP\App\Themes\ThemeService;
use Kenjiefx\ScratchPHP\Container;
use League\Container\ReflectionContainer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\App;

/**
 * HTTPRunner class implements the RunnerInterface for handling HTTP requests.
 * This runner is used when the application is running in a web server context.
 */
class HTTPRunner implements RunnerInterface
{

    private App $applicationRunner;

    /**
     * Load dependencies required for the HTTP runtime context.
     *
     * @return void
     */
    public function loadDependencies()
    {
        Container::get()->delegate(new ReflectionContainer());
        Container::get()->add(ConfigurationInterface::class, new ScratchJsonConfiguration());
        Container::get()->get(ScratchJsonExtManager::class)->load();
        $this->applicationRunner = AppFactory::create();

        $this->applicationRunner->get('/$editor', function (Request $request, Response $response, array $args) {
            try {
                $queryParams = $request->getQueryParams();
                $component = $queryParams['component'] ?? null;
                $this->assignTemplateService(new EditorTemplateService());
                $this->assignExporterService(new EditorExportService());
                Container::get()->get(ScratchJsonExtManager::class)->mount([
                    EditorExtension::class => []
                ]);
                $editorController = Container::get()->get(EditorController::class);
                $html = $editorController->render([
                    'component' => $component
                ]);
                $response->getBody()->write($html);
                return $response->withHeader('Content-Type', 'text/html')->withStatus(200);
            } catch (\Throwable $th) {
                $response->getBody()->write("Error: " . $th->getMessage());
                return $response->withStatus(code: 500)->withHeader('Content-Type', 'text/plain');
            }
        });

        $this->applicationRunner->get('/$assets/{assetName}', function (Request $request, Response $response, array $args) {
            // Assign the EditorTemplateService and EditorExportService to the container
            $this->assignTemplateService(new EditorTemplateService());
            $this->assignExporterService(new EditorExportService());
            $pagePreviewerController = Container::get()->get(EditorController::class);
            try {
                $assetName = $args['assetName'];
                $assetContent = $pagePreviewerController->retrieveAsset($assetName);
                $contentType = $this->convertFileExtToMimeType($assetName);
                $response->getBody()->write($assetContent);
                return $response->withHeader('Content-Type', $contentType)->withStatus(200);
            } catch (\Exception $e) {
                $response->getBody()->write('Asset not found: ' . $e->getMessage());
                return $response->withHeader('Content-Type', 'text/plain')->withStatus(404);
            }
        });

        $this->applicationRunner->get('/', function (Request $request, Response $response, array $args) {
            $templateService = $this->getDefaultTemplateService();
            $this->assignTemplateService($templateService);
            $this->assignExporterService(new PagePreviewerExportService());
            $pagePreviewerController = Container::get()->get(PagePreviewController::class);
            $html = $pagePreviewerController->renderHtml('index.json');
            $response->getBody()->write($html);
            return $response->withHeader('Content-Type', 'text/html')->withStatus(200);
        });

        $this->applicationRunner->get('/assets/{path:.*}', function (Request $request, Response $response, array $args) {
            // Assign the TemplateService and ExporterService to the container
            $templateService = $this->getDefaultTemplateService();
            $this->assignTemplateService($templateService);
            $this->assignExporterService(new PagePreviewerExportService());
            // Retrieve the asset content using the PagePreviewController
            $pagePreviewerController = Container::get()->get(PagePreviewController::class);
            try {
                $assetName = $args['path'];
                $assetContent = $pagePreviewerController->retrieveAsset($assetName);
                $mimeType = $this->convertFileExtToMimeType($assetName);
                $response->getBody()->write($assetContent);
                return $response->withHeader('Content-Type', $mimeType)->withStatus(200);
            } catch (\Exception $e) {
                $response->getBody()->write('Asset not found: ' . $e->getMessage());
                return $response->withHeader('Content-Type', 'text/plain')->withStatus(404);
            }
        });
        $this->applicationRunner->any('/{any:.*}', function (Request $request, Response $response, array $args) {
            // Assign the TemplateService and ExporterService to the container
            $this->assignTemplateService($this->getDefaultTemplateService());
            $this->assignExporterService(new PagePreviewerExportService());
            try {
                $anyPath = $args['any'];
                if (empty($anyPath)) {
                    $anyPath = 'index.json';
                }
                $anyPath = str_replace('/', DIRECTORY_SEPARATOR, $anyPath);
                // check if the path contains .html, if not, throw an error 
                if (!str_ends_with($anyPath, '.html')) {
                    throw new \Exception("Invalid path: $anyPath");
                }
                // Replace .html with .json to get the page json file
                $pageJson = str_replace('.html', '.json', $anyPath);
                $pagePreviewerController = Container::get()->get(PagePreviewController::class);
                $html = $pagePreviewerController->renderHtml($pageJson);
                $response->getBody()->write($html);
                return $response->withHeader('Content-Type', 'text/html')->withStatus(200);
            } catch (\Exception $e) {
                //throw $th;
                return $response->withHeader('Content-Type', 'text/plain')->withStatus(404);
            }
        });
    }

    private function assignTemplateService(TemplateServiceInterface $templateService)
    {
        Container::get()->add(TemplateServiceInterface::class, $templateService);
    }

    private function assignExporterService(ExporterInterface $exporterService)
    {
        Container::get()->add(ExporterInterface::class, $exporterService);
    }

    private function getDefaultTemplateService() {
        return new TemplateService(
            Container::get()->get(ConfigurationInterface::class),
            Container::get()->get(ThemeService::class),
            Container::get()->get(FileFactory::class),
        );
    }

    /**
     * Convert file extension to MIME type.
     *
     * @param string $assetName The name of the asset file.
     * @return string The MIME type corresponding to the file extension.
     */
    private function convertFileExtToMimeType(string $assetName) {
        $extension = pathinfo($assetName, PATHINFO_EXTENSION);
        switch ($extension) {
            case 'css':
                return 'text/css';
            case 'js':
                return 'application/javascript';
            case 'ico':
                return 'image/x-icon';
            case 'png':
                return 'image/png';
            case 'jpg':
            case 'jpeg':
                return 'image/jpeg';
            case 'gif':
                return 'image/gif';
            case 'svg':
                return 'image/svg+xml';
            default:
                return 'text/plain'; // Default fallback
        }
    }

    /**
     * Execute the HTTP context-specific logic.
     *
     * @return void
     */
    public function executeContext()
    {
        $this->applicationRunner->run();
    }
}