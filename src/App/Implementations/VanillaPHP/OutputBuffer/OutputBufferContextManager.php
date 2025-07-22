<?php 

namespace Kenjiefx\ScratchPHP\App\Implementations\VanillaPHP\OutputBuffer;

/**
 * OutputBufferContextManager is responsible for managing the lifecycle of
 * OutputBufferContext instances. It allows creating, resetting, and retrieving
 * the context.
 */
class OutputBufferContextManager {

    private static OutputBufferContext | null $instance = null;

    /**
     * A unique key used to reset the OutputBufferContext instance.
     * This key is generated when the context is created and must be provided
     * to reset the context.
     */
    private static string $rsetkey = '';

    /**
     * Creates a new OutputBufferContext instance.
     * @param OutputBufferContext $outputBufferContext
     * @throws \Exception
     * @return string A unique key to reset the context later.
     */
    public function create(OutputBufferContext $outputBufferContext): string {
        if (static::$instance !== null) {
            $message = "OutputBufferContext instance already exists.";
            throw new \Exception($message);
        }
        static::$instance = $outputBufferContext;
        static::$rsetkey = uniqid();
        return static::$rsetkey;
    }

    /**
     * Resets the OutputBufferContext instance.
     * @param string $key
     * @throws \Exception
     * @return void
     */
    public function reset(string $key) {
        if (static::$rsetkey !== $key) {
            $message = "Invalid key provided for resetting OutputBufferContext.";
            throw new \Exception($message);
        }
        if (static::$instance === null) {
            $message = "OutputBufferContext instance does not exist.";
            throw new \Exception($message);
        }
        static::$instance = null;
    }

    public function get(): OutputBufferContext {
        if (static::$instance === null) {
            $message = "OutputBufferContext instance does not exist.";
            throw new \Exception($message);
        }
        return static::$instance;
    }

}