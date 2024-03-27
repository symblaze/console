<?php

declare(strict_types=1);

namespace Symblaze\Console\IO\Helper;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\TableSeparator;

/**
 * A collection of methods to interact with the output.
 *
 * @internal
 */
trait OutputTrait
{
    protected function horizontalTable(array $headers, array $rows): void
    {
        $this->output->horizontalTable($headers, $rows);
    }

    protected function progressAdvance(int $step = 1): void
    {
        $this->output->progressAdvance($step);
    }

    protected function error(string|array $message): void
    {
        $this->output->error($message);
    }

    protected function progressFinish(): void
    {
        $this->output->progressFinish();
    }

    protected function title(string $message): void
    {
        $this->output->title($message);
    }

    protected function confirm(string $question, bool $default = true): bool
    {
        return $this->output->confirm($question, $default);
    }

    /**
     * Writes a message to the output and adds a newline at the end.
     */
    protected function line(string $message, ?string $style = null): void
    {
        $styled = $style ? "<$style>$message</$style>" : $message;

        $this->output->line($styled);
    }

    protected function note(string|array $message): void
    {
        $this->output->note($message);
    }

    protected function question(string|array $message): void
    {
        $this->line($message, 'question');
    }

    protected function definitionList(string|array|TableSeparator ...$list): void
    {
        $this->output->definitionList(...$list);
    }

    protected function table(array $headers, array $rows): void
    {
        $this->output->table($headers, $rows);
    }

    protected function progressStart(int $max = 0): void
    {
        $this->output->progressStart($max);
    }

    protected function warning(string|array $message): void
    {
        $this->output->warning($message);
    }

    protected function createProgressBar(int $max = 0): ProgressBar
    {
        return $this->output->createProgressBar($max);
    }

    protected function comment(string|array $message): void
    {
        $this->output->comment($message);
    }

    protected function info(string|array $message): void
    {
        $this->output->info($message);
    }

    protected function text(string|array $message): void
    {
        $this->output->text($message);
    }

    protected function success(string|array $message): void
    {
        $this->output->success($message);
    }

    protected function askHidden(string $question, ?callable $validator = null): mixed
    {
        return $this->output->askHidden($question, $validator);
    }

    protected function choice(string $question, array $choices, mixed $default = null, bool $multiSelect = false): mixed
    {
        return $this->output->choice($question, $choices, $default, $multiSelect);
    }

    protected function listing(array $elements): void
    {
        $this->output->listing($elements);
    }

    protected function caution(string|array $message): void
    {
        $this->output->caution($message);
    }

    protected function section(string $message): void
    {
        $this->output->section($message);
    }

    protected function ask(string $question, ?string $default = null, ?callable $validator = null): mixed
    {
        return $this->output->ask($question, $default, $validator);
    }
}
