<?php

namespace Kenjiefx\ScratchPHP\App\Configuration;
use Kenjiefx\ScratchPHP\App\Exceptions\ConfigurationException;

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

    private static CreateConfiguration $CreateConfiguration;

    private static ExtensionConfiguration $ExtensionConfiguration;

    /**
     * Loads strawberry configuration file
     */
    public static function load()
    {

        if (!static::$isInitialized) {

            # Making sure that the config json exists in the project directory
            if (!file_exists(Self::getConfigFilePath())) throw new ConfigurationException();

            # Next, we store the configuration data
            static::$configuration       = Self::unpackJson(file_get_contents(Self::getConfigFilePath()));
            static::$BuildConfiguration  = new BuildConfiguration(static::$configuration['build'] ?? []);
            static::$CreateConfiguration = new CreateConfiguration(static::$configuration['create'] ?? []);

            if (isset(static::$configuration['extensions'])) {
                static::$ExtensionConfiguration = new ExtensionConfiguration();
                static::$ExtensionConfiguration::mountExtensions(static::$configuration['extensions']);
            }

            static::$isInitialized = true;
        }
        
    }

    private static function unpackJson (string $configJson):array{
        return json_decode($configJson,TRUE);
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
        return static::$ExtensionConfiguration;
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

    public static function createConfig(){
        return static::$CreateConfiguration;
    }




}
