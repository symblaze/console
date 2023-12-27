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
}
