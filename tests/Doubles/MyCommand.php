<?php

declare(strict_types=1);

namespace Symblaze\Console\Tests\Doubles;

use Symblaze\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'acme:command {required_argument} {optional_argument?} {argument_with_value=default} {--O|option} {--OWV|option_with_value=} {--OWDV|option_with_default=default}')]
class MyCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return self::SUCCESS;
    }

    /**
     * Determine if the given argument is present.
     */
    public function hasArgument($name): bool
    {
        return $this->input->hasArgument($name) && ! is_null($this->argument($name));
    }

    /**
     * Determine if the given option is present.
     */
    public function hasOption($name): bool
    {
        return $this->input->hasOption($name) && ! is_null($this->option($name));
    }

    public function option(string $key): bool|array|string|null
    {
        return $this->input->getOption($key);
    }

    public function options(): array
    {
        return $this->input->getOptions();
    }

    /**
     * Get the value of a command argument.
     */
    public function argument(string $key): bool|array|string|null
    {
        return $this->input->getArgument($key);
    }

    /**
     * Get all the arguments passed to the command.
     */
    public function arguments(): array
    {
        return $this->input->getArguments();
    }
}
