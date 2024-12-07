<?php

namespace Functionil\Pipe;

/**
 * Invoke a single pipe (callable) with the subject in the pipeline.
 * This function also allows for partial application.
 *
 * @param mixed $subject
 * @param array $arguments
 * @param callable $pipe
 * @return Pipeline
 * @internal
 */
function invoke_with(mixed $subject, array $arguments, callable $pipe): Pipeline {
    foreach ($arguments as $idx => $argument) {
        if (!($argument instanceof Placeholder)) {
            continue;
        }

        $arguments[$idx] = $subject;
        $substituted = true;
    }

    if (empty($arguments)) $arguments = [$subject];
    else if (!($substituted ?? false)) array_unshift($arguments, $subject);

    return Pipeline::new($pipe(...$arguments));
}

readonly class Pipeline
{
    private final function __construct(private mixed $subject) {}

    /**
     * Construct a new `Pipe` instance.
     *
     * @param mixed $subject
     * @return static
     */
    public static function new(mixed $subject): static
    {
        return new static($subject);
    }

    /**
     * Get the subject stored in the pipeline.
     *
     * @return mixed
     */
    public function get(): mixed
    {
        return $this->subject;
    }

    /**
     * Pass the subject through a pipe that only takes one argument.
     *
     * @param callable $pipe
     * @return static
     */
    public function _(callable $pipe): static
    {
        return new static($pipe($this->subject));
    }

    /**
     * Pass the subject through a pipe (function) that doesn't take
     * any arguments besides the subject itself.
     *
     * @param callable-string $name
     * @return static
     */
    public function __get(string $name): static
    {
        return new static($name($this->subject));
    }

    /**
     * Pass the subject through a pipe (function) that may have many arguments,
     * partial application may be applied.
     *
     * @param callable-string $name
     * @param array $arguments
     * @return static
     */
    public function __call(string $name, array $arguments): static
    {
        return invoke_with($this->subject, $arguments, $name);
    }

    public function __clone()
    {
        if (is_object($this->subject)) {
            $this->subject = clone $this->subject;
        }
    }
}