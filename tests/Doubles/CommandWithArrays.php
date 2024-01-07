<?php

declare(strict_types=1);

namespace Symblaze\Console\Tests\Doubles;

use Symblaze\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'acme:array:command {argument_array*} {--option_array=*}')]
class CommandWithArrays extends Command
{
}
