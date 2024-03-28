<?php

declare(strict_types=1);

namespace Symblaze\Console\IO;

use Symfony\Component\Console\Formatter\NullOutputFormatter;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * The Symblaze output helper class.
 *
 * @internal - This class is for internal use only
 */
class Output extends SymfonyStyle
{
    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $formatter = $output->getFormatter() ?? new NullOutputFormatter();
        $output->setFormatter($formatter);
        
        parent::__construct($input, $output);
    }
}
