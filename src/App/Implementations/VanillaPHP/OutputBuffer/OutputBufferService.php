<?php 

namespace Kenjiefx\ScratchPHP\App\Implementations\VanillaPHP\OutputBuffer;

/**
 * OutputBufferService is responsible for capturing the output of a PHP file
 * and returning it as a string. It uses output buffering to capture the output
 * and handles errors by throwing an ErrorException.
 */
class OutputBufferService {

    public function __construct(
        private readonly OutputBufferContextManager $contextManager
    ) {}

    /**
     * Captures the output of a PHP file specified by $functionsPath and $indexFilePath.
     * It uses output buffering to capture the output and returns it as a string.
     * 
     * @param string $functionsPath The path to the PHP file containing functions.
     * @param string $indexFilePath The path to the main PHP file to be executed.
     * @param OutputBufferContext $contextData Context data to be used during output buffering.
     * @return string The captured output as a string.
     * @throws \Exception If an error occurs during the execution of the PHP files.
     */
    public function capture(
        string $functionsPath,
        string $indexFilePath, 
        OutputBufferContext $contextData
    ): string {
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
        });
        $resetKey = $this->contextManager->create($contextData);
        ob_start();
        try {
            include_once $functionsPath;
            require $indexFilePath;
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        } catch (\Exception $e) {
            ob_end_clean();
            throw $e;
        } finally {
            restore_error_handler();
            $this->contextManager->reset($resetKey);
        }
    }

}