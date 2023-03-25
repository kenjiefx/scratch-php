<?php

namespace Kenjiefx\ScratchPHP\App\Configuration;
use Kenjiefx\ScratchPHP\App\Exceptions\ConfigurationException;
use Kenjiefx\ScratchPHP\App\Extensions\ExtensionsRegistry;
use Kenjiefx\ScratchPHP\App\Factory\ContainerFactory;
use Kenjiefx\ScratchPHP\App\Interfaces\ExtensionsInterface;

class AppSettings
{
    /**
     * Tells whether the configuration has been loaded 
     * already or not
     */
    private static bool $hasLoaded = false;

    /**
     * The name of the strawberry configuration file 
     */
    private static string $fileName = 'scratch.config.json';

    /**
     * Contains a list of the configurable settings within
     * the framework and their values
     */
    private static array $appConfiguration = [];

    /**
     * Build configuration manager
     */
    private static BuildConfiguration $buildConfiguration; 

    private static ExtensionsRegistry $extensionsRegistry;

    /**
     * Loads strawberry configuration file
     */
    public static function load()
    {

        $error = 'Strawberry configuration not found. Please make sure that the file ';
        $error .= 'strawberry.config.json exists in your root directory and is valid. ';
        $error .= 'To generate the configuration file, use init conmmand.';

        if (!static::$hasLoaded) {
            $path = Self::getConfigFilePath();
            if (!file_exists($path)) {
                throw new ConfigurationException($error);
            }
            $config = json_decode(file_get_contents($path),TRUE);
            static::$appConfiguration = $config;
            if (isset($config['build'])) {
                static::$buildConfiguration = new BuildConfiguration($config['build']);
            }

            static::$extensionsRegistry = new ExtensionsRegistry();
            if (isset($config['extensions'])) {
                foreach ($config['extensions'] as $extensionNamespace => $settings) {
                    $objectReflection = new \ReflectionClass($extensionNamespace);
                    if (!$objectReflection->implementsInterface(ExtensionsInterface::class)) {
                        $extError = 'Unsupported Extension! Please make sure that the extension you are using "'.$extensionNamespace.'" ';
                        $extError .= 'is implementing the Extension Interface "'.ExtensionsInterface::class.'"';
                        throw new \Exception($extError);
                    }
                    $objectExtension = ContainerFactory::create()->get($extensionNamespace);
                    static::$extensionsRegistry->addExtension($objectExtension);
                }
            }

            static::$hasLoaded = true;
        }
        
    }

    /**
     * Returns the path of the strawberry configuration file
     */
    private static function getConfigFilePath()
    {
        return static::$fileName;
    }

    public static function getThemeName()
    {
        return static::$appConfiguration['theme'] ?? 'infinity';
    }

    public static function getExportDirPath(){
        return static::$appConfiguration['exportDir'] ?? '/dist';
    }

    public static function build()
    {
        return static::$buildConfiguration;
    }

    public static function extensions(){
        return static::$extensionsRegistry;
    }

    public static function create(
        string $themeName
    ){
        $config = [
            'theme' => $themeName,
            'exportDir' => '/dist',
            'build' => [
                'useRandomAssetsFileNames' => false
            ],
            'extensions' => []
        ];
        $path = ROOT.'/'.Self::getConfigFilePath(); 
        if (!file_exists($path)) {
            file_put_contents($path,json_encode($config,JSON_PRETTY_PRINT));
            return true;
        }
        return false;
    }


}
