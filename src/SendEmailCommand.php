<?php

declare(strict_types=1);

namespace Symblaze\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(name: 'mail:send {user} {--queue=}', description: 'Send email to user')]
final class SendEmailCommand extends Command
{
    protected function configure(): void
    {
        $this->addArgument('user', InputArgument::REQUIRED);
        $this->addOption('queue', null, InputArgument::REQUIRED);
    }
}
