<?php

declare(strict_types=1);

namespace Symblaze\Console\IO;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\TrimmedBufferOutput;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * The Symblaze output helper class.
 *
 * @internal - This class is for internal use only
 */
class Output extends SymfonyStyle
{
    private TrimmedBufferOutput $bufferedOutput;

    public function __construct(InputInterface $input, OutputInterface $output)
    {
        if (is_null($output->getFormatter())) {
            $output = new NullOutput();
        }

        parent::__construct($input, $output);

        $this->bufferedOutput = new TrimmedBufferOutput(
            DIRECTORY_SEPARATOR === '\\' ? 4 : 2,
            $output->getVerbosity(),
            false,
            clone $output->getFormatter()
        );
    }

    public function listing(array $elements): void
    {
        $this->autoPrependText();

        $elements = array_map(static fn ($element) => sprintf(' ➜ %s', $element), $elements);

        $this->writeln($elements);
        $this->newLine();
    }

    public function comment(string|array $message): void
    {
        $this->write(sprintf('<fg=default;bg=default>➜ %s</>', $message));
        $this->newLine();
    }

    public function success(string|array $message): void
    {
        $this->write(sprintf('<fg=green;bg=default>✔ %s</>', $message));
        $this->newLine();
    }

    public function error(string|array $message): void
    {
        $this->write(sprintf('<fg=red;bg=default>✘ %s</>', $message));
        $this->newLine();
    }

    public function warning(string|array $message): void
    {
        $this->write(sprintf('<fg=yellow;bg=default>⚠ %s</>', $message));
        $this->newLine();
    }

    public function note(string|array $message): void
    {
        $this->write(sprintf('<fg=cyan;bg=default>➜ %s</>', $message));
        $this->newLine();
    }

    public function info(string|array $message): void
    {
        $this->write(sprintf('<fg=blue;bg=default>ℹ %s</>', $message));
        $this->newLine();
    }

    public function caution(string|array $message): void
    {
        $this->write(sprintf('<fg=black;bg=yellow>! %s</>', $message));
        $this->newLine();
    }

    public function line(string $message, ?string $style = null): void
    {
        $styled = $style ? "<$style>$message</$style>" : $message;

        $this->writeln($styled);
    }

    public function question(string|array $message): void
    {
        $this->write(sprintf('<fg=black;bg=cyan>? %s</>', $message));
        $this->newLine();
    }

    public function title(string|array $message): void
    {
        $this->write(sprintf('<fg=default;bg=default;options=underscore> %s </>', $message));
        $this->newLine();
    }

    public function labeledTitle(
        string $label,
        string $title,
        string $labelStyle = 'fg=white;bg=green;options=bold',
        string $labelPrefix = '➜ ',
        string $labelSeparator = ' ',
    ): void {
        $this->write(sprintf('<%s>%s%s%s</>', $labelStyle, $labelPrefix, $label, $labelSeparator));
        $this->write($title);
        $this->newLine();
    }

    private function autoPrependText(): void
    {
        $fetched = $this->bufferedOutput->fetch();
        // Prepend new line if last char isn't EOL:
        if ($fetched && ! str_ends_with($fetched, "\n")) {
            $this->newLine();
        }
    }
}
