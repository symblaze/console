<?php

declare(strict_types=1);

namespace Symblaze\Console;

use Symblaze\Console\IO\InputTrait;
use Symblaze\Console\IO\Output;
use Symblaze\Console\IO\OutputTrait;
use Symblaze\Console\IO\Style\StyleFactory;
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
    use InputTrait;
    use OutputTrait;

    protected InputInterface|IO\InputInterface $input;
    protected IO\OutputInterface $output;

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
        $this->setOutput(new Output(StyleFactory::create($input, $output)));

        return parent::run($input, $output);
    }

    public function getInput(): IO\InputInterface|InputInterface
    {
        return $this->input;
    }

    public function setInput(IO\InputInterface|InputInterface $input): static
    {
        $this->input = $input;

        return $this;
    }

    public function getOutput(): IO\OutputInterface
    {
        return $this->output;
    }

    public function setOutput(IO\OutputInterface $output): static
    {
        $this->output = $output;

        return $this;
    }
}
