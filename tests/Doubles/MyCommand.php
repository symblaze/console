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
}
