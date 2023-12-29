<?php

declare(strict_types=1);

namespace Symblaze\Console;

use Illuminate\Console\Concerns\InteractsWithIO;
use Illuminate\Console\OutputStyle;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Command extends SymfonyCommand
{
    use InteractsWithIO;

    private const VERBOSITY_MAP = [
        'v' => OutputInterface::VERBOSITY_VERBOSE,
        'vv' => OutputInterface::VERBOSITY_VERY_VERBOSE,
        'vvv' => OutputInterface::VERBOSITY_DEBUG,
        'quiet' => OutputInterface::VERBOSITY_QUIET,
        'normal' => OutputInterface::VERBOSITY_NORMAL,
    ];

    protected function configure(): void
    {
        [$name, $arguments, $options] = Parser::parse(static::getDefaultName());

        $this->setName($name);
        $this->getDefinition()->addArguments($arguments);
        $this->getDefinition()->addOptions($options);
    }

    public function run(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = new OutputStyle($input, $output);

        return parent::run($input, $output);
    }

    /**
     * Determine if the given argument is present.
     */
    protected function hasArgument($name): bool
    {
        return $this->input->hasArgument($name) && ! is_null($this->argument($name));
    }

    /**
     * Determine if the given option is present.
     */
    protected function hasOption($name): bool
    {
        return $this->input->hasOption($name) && ! is_null($this->option($name));
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
     * Get the value of a command argument.
     */
    protected function argument(string $key): bool|array|string|null
    {
        return $this->input->getArgument($key);
    }

    /**
     * Get all the arguments passed to the command.
     */
    protected function arguments(): array
    {
        return $this->input->getArguments();
    }

    /**
     * Writes a message to the output and adds a newline at the end.
     */
    protected function line(string $message, ?string $style = null, string|int $verbosity = 'normal'): void
    {
        $styled = $style ? "<$style>$message</$style>" : $message;

        $this->output->writeln($styled, $this->parseVerbosity($verbosity));
    }
    
    private function parseVerbosity(int|string $level): int
    {
        if (is_int($level)) {
            return $level;
        }

        return self::VERBOSITY_MAP[$level] ?? $this->verbosity;
    }
}
