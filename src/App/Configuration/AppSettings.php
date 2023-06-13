<?php

namespace Kenjiefx\ScratchPHP\App\Configuration;
use Kenjiefx\ScratchPHP\App\Exceptions\ConfigurationException;
use Kenjiefx\ScratchPHP\App\Exceptions\MustImplementExtensionInterfaceException;
use Kenjiefx\ScratchPHP\App\Extensions\ExtensionsRegistry;
use Kenjiefx\ScratchPHP\App\Factory\ContainerFactory;
use Kenjiefx\ScratchPHP\App\Interfaces\ExtensionsInterface;

class AppSettings
{
    /** Tells whether the configuration has been loaded already or not */
    private static bool $isInitialized = false;

    /**
     * The name of the strawberry configuration file 
     */
    private static string $fileName = 'scratch.config.json';

    /**
     * Contains a list of the configurable settings within
     * the framework and their values
     */
    private static array $configuration = [];

    /**
     * Build configuration manager
     */
    private static BuildConfiguration $BuildConfiguration; 

    private static ExtensionsRegistry $ExtensionsRegistry;

    /**
     * Loads strawberry configuration file
     */
    public static function load()
    {

        if (!static::$isInitialized) {

            # Making sure that the config json exists in the project directory
            if (!file_exists(Self::getConfigFilePath())) throw new ConfigurationException();

            # Next, we store the configuration data
            static::$configuration = Self::unpackJson(file_get_contents(Self::getConfigFilePath()));
            
            if (isset(static::$configuration['build'])) {
                static::$BuildConfiguration = new BuildConfiguration(static::$configuration['build']);
            }

            static::$ExtensionsRegistry = new ExtensionsRegistry();
            if (isset(static::$configuration['extensions'])) {
                Self::mountExtensions();
            }

            static::$isInitialized = true;
        }
        
    }

    private static function unpackJson (string $configJson):array{
        return json_decode($configJson,TRUE);
    }

    private static function mountExtensions(){

        foreach (static::$configuration['extensions'] as $extensionNamespace => $extensionSettings) {
            $ReflectionObject = new \ReflectionClass($extensionNamespace);

            if (!$ReflectionObject->implementsInterface(ExtensionsInterface::class)) {
                throw new MustImplementExtensionInterfaceException($extensionNamespace);
            }
            $ExtensionObject = ContainerFactory::create()->get($extensionNamespace);
            
            foreach ($ReflectionObject->getMethods() as $ReflectionMethod) {
                foreach ($ReflectionMethod->getAttributes() as $ReflectionAttribute) {
                    $Attribute = $ReflectionAttribute->newInstance();
                }
            }

            static::$ExtensionsRegistry->registerExtension($ExtensionObject);
        }
    }

    /** Returns the path of the strawberry configuration file */
    private static function getConfigFilePath()
    {
        return ROOT.'/'.static::$fileName;
    }

    public static function getThemeName()
    {
        return static::$configuration['theme'] ?? 'slate';
    }

    public static function getExportDirPath(){
        return static::$configuration['exportDir'] ?? '/dist';
    }

    public static function build()
    {
        return static::$BuildConfiguration;
    }

    public static function extensions(){
        return static::$ExtensionsRegistry;
    }

    public static function create(string $themeName){
        $config = [
            'theme' => $themeName,
            'exportDir' => '/dist',
            'build' => [
                'useRandomAssetsFileNames' => false
            ],
            'extensions' => []
        ];
        $path = Self::getConfigFilePath(); 
        if (!file_exists($path)) {
            file_put_contents($path,json_encode($config,JSON_PRETTY_PRINT));
            return true;
        }
        return false;
    }


}
