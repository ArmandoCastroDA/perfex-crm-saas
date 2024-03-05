<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class to handle timeout on a block of code.
 *
 * This class allows you to set a time limit for the execution of a block of code.
 * If the code exceeds the specified time limit, a RuntimeException is thrown.
 *
 * Usage:
 * Timeouter::limit(5, 'Timeout occurred.'); // Set a time limit of 5 seconds
 * // Code block to be executed within the time limit
 * Timeouter::end(); // End the time limit
 *
 * @see https://stackoverflow.com/a/54049448
 */
class Timeouter
{
    private static $start_time = FALSE;
    private static $timeout;

    /**
     * Set a time limit for the execution of a block of code.
     *
     * @param int $seconds Time in seconds.
     * @param string|null $error_msg Error message to be thrown when the time limit is exceeded.
     */
    public static function limit($seconds, $error_msg = NULL): void
    {
        self::$start_time = microtime(TRUE);
        self::$timeout = (float) $seconds;
        register_tick_function([self::class, 'tick'], $error_msg);
    }

    /**
     * End the time limit for the execution of the code block.
     */
    public static function end(): void
    {
        unregister_tick_function([self::class, 'tick']);
    }

    /**
     * Tick function called for each tick of the PHP interpreter.
     *
     * Compares the elapsed time with the time limit, and throws a RuntimeException if the time limit is exceeded.
     *
     * @param string|null $error Error message to be thrown when the time limit is exceeded.
     */
    public static function tick($error): void
    {
        if ((microtime(TRUE) - self::$start_time) > self::$timeout) {
            throw new \RuntimeException($error ?? 'Your code took too much time.');
        }
    }

    /**
     * Perform a small step to allow the tick function to be executed.
     */
    public static function step(): void
    {
        usleep(1);
    }
}
