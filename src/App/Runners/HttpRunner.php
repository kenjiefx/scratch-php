<?php 

namespace Kenjiefx\ScratchPHP\App\Runners;
use Kenjiefx\ScratchPHP\App\HTTP\Editor\EditorController;
use Kenjiefx\ScratchPHP\App\HTTP\Editor\EditorPageCollectorService;
use Kenjiefx\ScratchPHP\App\HTTP\Editor\EditorThemeService;
use Kenjiefx\ScratchPHP\App\HTTP\Exporter\HttpResourceExportService;
use Kenjiefx\ScratchPHP\App\HTTP\Previewer\PagePreviewController;
use Kenjiefx\ScratchPHP\App\Implementations\ScratchJSON\ScratchJSONConfiguration;
use Kenjiefx\ScratchPHP\App\Implementations\PageJSON\PageJSONCollector;
use Kenjiefx\ScratchPHP\App\Implementations\ScratchJSON\ScratchJSONExtensionCollector;
use Kenjiefx\ScratchPHP\App\Implementations\ThemeManager\ThemeService;
use Kenjiefx\ScratchPHP\App\Implementations\VanillaPHP\VanillaPHPBuilder;
use Kenjiefx\ScratchPHP\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\App;

/**
 * HTTPRunner class implements the RunnerInterface for handling HTTP requests.
 * This runner is used when the application is running in a web server context.
 */
class HTTPRunner implements RunnerInterface {


    private App $applicationRunner;

    /**
     * Load dependencies required for the HTTP runtime context.
     *
     * @return void
     */
    public function loadDependencies() {
        Container::create();
        $this->applicationRunner = AppFactory::create();
        $this->applicationRunner->get('/assets[/{params:.*}]', function (Request $request, Response $response, array $args) {
            try {
                Container::bind()->ConfigurationInterface(Container::get(ScratchJSONConfiguration::class));
                Container::bind()->PageCollectorInterface(Container::get(PageJSONCollector::class));
                Container::bind()->ThemeServiceInterface(Container::get(ThemeService::class));
                Container::bind()->ExtensionCollectorInterface(Container::get(ScratchJSONExtensionCollector::class));
                Container::bind()->ExportServiceInterface(Container::get(HttpResourceExportService::class));
                Container::bind()->BuildServiceInterface(Container::get(VanillaPHPBuilder::class));
                $controller = Container::get(PagePreviewController::class);
                return $controller->assets($request, $response);
            } catch (\Exception $exception) {
                $response->getBody()->write('Error: ' . $exception->getMessage());
                return $response->withHeader('Content-Type', 'text/plain')->withStatus(500);
            }
        });
        $this->applicationRunner->get('/$editor', function (Request $request, Response $response, array $args) {
            try {
                Container::bind()->ConfigurationInterface(Container::get(ScratchJSONConfiguration::class));
                Container::bind()->PageCollectorInterface(Container::get(EditorPageCollectorService::class));
                Container::bind()->ThemeServiceInterface(Container::get(EditorThemeService::class));
                Container::bind()->ExtensionCollectorInterface(Container::get(ScratchJSONExtensionCollector::class));
                Container::bind()->ExportServiceInterface(Container::get(HttpResourceExportService::class));
                Container::bind()->BuildServiceInterface(Container::get(VanillaPHPBuilder::class));
                $controller = Container::get(EditorController::class);
                $controller->page($request, $response);
                return $response->withHeader('Content-Type', 'text/html')->withStatus(200);
            } catch (\Exception $exception) {
                $response->getBody()->write('Error: ' . $exception->getMessage());
                return $response->withHeader('Content-Type', 'text/plain')->withStatus(500);
            }
        });
        $this->applicationRunner->get('/$assets[/{params:.*}]', function (Request $request, Response $response, array $args) {
            try {
                Container::bind()->ConfigurationInterface(Container::get(ScratchJSONConfiguration::class));
                Container::bind()->PageCollectorInterface(Container::get(EditorPageCollectorService::class));
                Container::bind()->ThemeServiceInterface(Container::get(EditorThemeService::class));
                Container::bind()->ExtensionCollectorInterface(Container::get(ScratchJSONExtensionCollector::class));
                Container::bind()->ExportServiceInterface(Container::get(HttpResourceExportService::class));
                Container::bind()->BuildServiceInterface(Container::get(VanillaPHPBuilder::class));
                $controller = Container::get(EditorController::class);
                return $controller->editorAssets($request, $response);
            } catch (\Exception $exception) {
                $response->getBody()->write('Error: ' . $exception->getMessage());
                return $response->withHeader('Content-Type', 'text/plain')->withStatus(500);
            }
        });
        $this->applicationRunner->any('/{routes:.*}', function (Request $request, Response $response, array $args) {
            try {
                Container::bind()->ConfigurationInterface(Container::get(ScratchJSONConfiguration::class));
                Container::bind()->PageCollectorInterface(Container::get(PageJSONCollector::class));
                Container::bind()->ThemeServiceInterface(Container::get(ThemeService::class));
                Container::bind()->ExtensionCollectorInterface(Container::get(ScratchJSONExtensionCollector::class));
                Container::bind()->ExportServiceInterface(Container::get(HttpResourceExportService::class));
                Container::bind()->BuildServiceInterface(Container::get(VanillaPHPBuilder::class));
                $controller = Container::get(PagePreviewController::class);
                $controller->page($request, $response);
                return $response->withHeader('Content-Type', 'text/html')->withStatus(200);
            } catch (\Exception $exception) {
                $response->getBody()->write('Error: ' . $exception->getMessage());
                return $response->withHeader('Content-Type', 'text/plain')->withStatus(500);
            }
        });
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