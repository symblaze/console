<?php

declare(strict_types=1);

namespace Symblaze\Console;

use RuntimeException;
use Symblaze\Console\IO\IOTrait;
use Symblaze\Console\IO\Output;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @psalm-api      - This file is part of the symblaze/console package.
 *
 * @psalm-suppress PropertyNotSetInConstructor - The properties are set in the run method.
 */
abstract class Command extends SymfonyCommand
{
    use IOTrait;

    protected InputInterface $input;
    protected Output $output;

    protected function configure(): void
    {
        [$name, $arguments, $options] = Parser::parse(static::getDefaultName());

        $this->setName($name);
        $this->getDefinition()->addArguments($arguments);
        $this->getDefinition()->addOptions($options);
    }

    public function run(InputInterface $input, OutputInterface $output): int
    {
        $this->setInput($input);
        $output = $output instanceof Output ? $output : new Output($input, $output);
        $this->setOutput($output);

        return parent::run($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (method_exists($this, 'handle')) {
            $result = $this->handle();
            assert(is_int($result), 'The handle method must return an integer.');

            return $result;
        }

        throw new RuntimeException('Either the `handle()` method must be implemented or the `execute()` method must be overridden.');
    }

    public function getInput(): InputInterface
    {
        return $this->input;
    }

    public function setInput(InputInterface $input): static
    {
        $this->input = $input;

        return $this;
    }

    public function getOutput(): Output
    {
        return $this->output;
    }

    public function setOutput(Output $output): static
    {
        $this->output = $output;

        return $this;
    }
}
