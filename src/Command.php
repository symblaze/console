<?php

declare(strict_types=1);

namespace Symblaze\Console;

use Symblaze\Console\IO\InputTrait;
use Symblaze\Console\IO\OutputTrait;
use Symblaze\Console\IO\Style\StyleFactory;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @psalm-api      - This file is part of the symblaze/console package.
 *
 * @psalm-suppress PropertyNotSetInConstructor - The properties are set in the run method.
 */
abstract class Command extends SymfonyCommand
{
    use InputTrait;
    use OutputTrait;

    protected InputInterface $input;
    protected SymfonyStyle $output;

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
        $this->output = StyleFactory::create($input, $output);

        return parent::run($input, $output);
    }
}
