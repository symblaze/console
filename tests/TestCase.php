<?php

declare(strict_types=1);

namespace Symblaze\Console\Tests;

use ReflectionObject;
use Symblaze\Console\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected OutputInterface $output;

    protected function executeCommand(Command $command, array $input = [], array $options = []): int
    {
        $input = new ArrayInput($input);
        $input->setStream($this->createStream($options));

        if (isset($options['interactive'])) {
            $input->setInteractive($options['interactive']);
        }

        $options['decorated'] = $options['decorated'] ?? false;

        return $command->run($input, $this->output = $this->initOutput($options));
    }

    protected function getDisplay(): string
    {
        rewind($this->output->getStream());

        $display = stream_get_contents($this->output->getStream());

        return ltrim(str_replace(PHP_EOL, "", $display));
    }

    private function createStream(array $inputs)
    {
        $stream = fopen('php://memory', 'rb+');

        foreach ($inputs as $input) {
            fwrite($stream, $input.PHP_EOL);
        }

        rewind($stream);

        return $stream;
    }

    private function initOutput(array $options): OutputInterface
    {
        $captureStreamsIndependently = $options['capture_stderr_separately'] ?? false;
        if (! $captureStreamsIndependently) {
            $output = new StreamOutput(fopen('php://memory', 'wb'));
            if (isset($options['decorated'])) {
                $output->setDecorated($options['decorated']);
            }
            if (isset($options['verbosity'])) {
                $output->setVerbosity($options['verbosity']);
            }

            return $output;
        }

        $output = new ConsoleOutput(
            $options['verbosity'] ?? OutputInterface::VERBOSITY_NORMAL,
            $options['decorated'] ?? null
        );

        $errorOutput = new StreamOutput(fopen('php://memory', 'wb'));
        $errorOutput->setFormatter($output->getFormatter());
        $errorOutput->setVerbosity($output->getVerbosity());
        $errorOutput->setDecorated($output->isDecorated());

        $reflectedOutput = new ReflectionObject($output);
        $strErrProperty = $reflectedOutput->getProperty('stderr');
        $strErrProperty->setValue($output, $errorOutput);

        $reflectedParent = $reflectedOutput->getParentClass();
        $streamProperty = $reflectedParent->getProperty('stream');
        $streamProperty->setValue($output, fopen('php://memory', 'wb'));

        return $output;
    }
}
