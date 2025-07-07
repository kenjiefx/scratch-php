<?php 

namespace Kenjiefx\ScratchPHP\App\Builders;

/**
 * BuildMessageChannel serves as a message channel for build messages. 
 * This channel will serve as a communication channel for between 
 * the Theme API and the HTML Builder.
 * 
 * For example, if the Theme API wants to retrieve the ComponentRegistry, 
 * it can send a message to the HTML Builder via this channel.
 * 
 * The HTML Builder can then listen for this message and respond accordingly.
 */
class BuildMessageChannel {

    private static array $listeners = [];

    /**
     * Sends a message to the channel.
     * @param \Kenjiefx\ScratchPHP\App\Builders\BuildMessage $message
     * @param array $args
     */
    public static function post(
        BuildMessage $message,
        ...$args
    ){
        if (!isset(static::$listeners[$message->value])) {
            return null; // No listeners for this message
        }
        $callback = static::$listeners[$message->value];
        return call_user_func($callback, ...$args);
    }

    /**
     * Registers a listener for a specific message.
     * @param \Kenjiefx\ScratchPHP\App\Builders\BuildMessage $message
     * @param callable $callback
     * @return void
     */
    public static function addListener(
        BuildMessage $message,
        callable $callback
    ){
        // Listen for messages
        if (!isset(static::$listeners[$message->value])) {
            static::$listeners[$message->value] = $callback;
        }
    }

    /**
     * Removes all listeners for messages.
     * @return void
     */
    public static function removeListeners(){
        // Remove listeners for messages
        static::$listeners = [];
    }


}