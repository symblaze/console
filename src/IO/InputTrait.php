<?php

declare(strict_types=1);

namespace Symblaze\Console\IO;

/**
 * A collection of methods to interact with the input.
 *
 * @internal
 */
trait InputTrait
{
    /**
     * Get the value of a command argument.
     */
    protected function argument(string $key): bool|array|string|null
    {
        return $this->input->getArgument($key);
    }

    protected function option(string $key): bool|array|string|null
    {
        return $this->input->getOption($key);
    }

    protected function options(): array
    {
        return $this->input->getOptions();
    }

    /**
     * Determine if the given argument is present.
     */
    protected function hasArgument($name): bool
    {
        return $this->input->hasArgument($name) && ! is_null($this->argument($name));
    }

    /**
     * Get all the arguments passed to the command.
     */
    protected function arguments(): array
    {
        return $this->input->getArguments();
    }

    /**
     * Determine if the given option is present.
     */
    protected function hasOption($name): bool
    {
        return $this->input->hasOption($name) && ! is_null($this->option($name));
    }
}
