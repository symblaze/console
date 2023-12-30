<?php

declare(strict_types=1);

namespace Symblaze\Console\Tests\Doubles;

use Symblaze\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'acme:optional:command {optional_argument_list?*}')]
class CommandWithOptionalArgumentArray extends Command
{
}
