<?php

declare(strict_types=1);

namespace Symblaze\Console\IO\Style;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Old versions of Symfony do not add return data types to the output interface.
 * This class is a workaround to allow mocking the output interface in tests.
 *
 * @internal
 */
final class LegacySymfonyStyle extends SymfonyStyle
{
    public function __construct(InputInterface $input)
    {
        parent::__construct($input, new NullOutput());
    }
}
