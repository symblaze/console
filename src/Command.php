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

    public function __construct(
        ?string $name = null,
        ?IO\InputInterface $input = null,
        ?IO\OutputInterface $output = null
    ) {
        parent::__construct($name);

        if (! is_null($input)) {
            $this->input = $input;
        }

        if (! is_null($output)) {
            $this->output = $output;
        }
    }

    protected function configure(): void
    {
        [$name, $arguments, $options] = Parser::parse(static::getDefaultName());

        $this->setName($name);
        $this->getDefinition()->addArguments($arguments);
        $this->getDefinition()->addOptions($options);
    }

    /**
     * @psalm-suppress RedundantPropertyInitializationCheck - The $input and $output are optional in the constructor.
     */
    public function run(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;

        $this->output = $this->output ?? new Output(StyleFactory::create($input, $output));

        return parent::run($input, $output);
    }
}
