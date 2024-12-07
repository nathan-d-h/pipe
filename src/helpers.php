<?php

use Functionil\Pipe\Pipeline;
use Functionil\Pipe\Placeholder;

if (!defined('_')) {
    define('_', new Placeholder);
}

if (!function_exists('pipe')) {
    /**
     * Construct a new `Pipe` instance.
     *
     * @param mixed $subject
     * @return Pipeline
     */
    function pipe(mixed $subject): Pipeline {
        return Pipeline::new($subject);
    }
}

if (!function_exists('take')) {
    /**
     * Construct a new `Pipe` instance.
     *
     * @param mixed $subject
     * @return Pipeline
     */
    function take(mixed $subject): Pipeline {
        return Pipeline::new($subject);
    }
}