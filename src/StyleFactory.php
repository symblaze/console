<?php

declare(strict_types=1);

namespace Symblaze\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class StyleFactory
{
    public static function create(InputInterface $input, OutputInterface $output): SymfonyStyle
    {
        return is_null($output->getFormatter()) ?
            new LegacySymfonyStyle($input) :
            new SymfonyStyle($input, $output);
    }
}
