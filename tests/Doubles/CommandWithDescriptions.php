<?php

declare(strict_types=1);

namespace Symblaze\Console\Tests\Doubles;

use Symblaze\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'acme:descriptions:command {argument : Argument description} {--O|option : Option description}')]
class CommandWithDescriptions extends Command
{
}
