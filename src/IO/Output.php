<?php

declare(strict_types=1);

namespace Symblaze\Console\IO;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * The Symblaze output helper class.
 *
 * @internal - This class is for internal use only
 */
class Output
{
    public function __construct(private readonly SymfonyStyle $style)
    {
    }

    public function line(string $message): void
    {
        $this->style->writeln($message);
    }

    public function info(string $message): void
    {
        $this->style->info($message);
    }

    public function comment(string $message): void
    {
        $this->style->comment($message);
    }

    public function error(string $message): void
    {
        $this->style->error($message);
    }

    public function warning(string $message): void
    {
        $this->style->warning($message);
    }

    public function success(string $message): void
    {
        $this->style->success($message);
    }

    public function title(string $message): void
    {
        $this->style->title($message);
    }

    public function section(string $message): void
    {
        $this->style->section($message);
    }

    public function text(string $message): void
    {
        $this->style->text($message);
    }

    public function listing(array $elements): void
    {
        $this->style->listing($elements);
    }

    public function table(array $headers, array $rows): void
    {
        $this->style->table($headers, $rows);
    }

    public function horizontalTable(array $headers, array $rows): void
    {
        $this->style->horizontalTable($headers, $rows);
    }

    public function definitionList(string|array|TableSeparator ...$list): void
    {
        $this->style->definitionList(...$list);
    }

    public function note(string $message): void
    {
        $this->style->note($message);
    }

    public function caution(string $message): void
    {
        $this->style->caution($message);
    }

    public function ask(string $question, ?string $default = null, ?callable $validator = null): mixed
    {
        return $this->style->ask($question, $default, $validator);
    }

    public function askHidden(string $question, ?callable $validator = null): mixed
    {
        return $this->style->askHidden($question, $validator);
    }

    public function confirm(string $question, bool $default = true): bool
    {
        return $this->style->confirm($question, $default);
    }

    public function choice(string $question, array $choices, mixed $default = null, bool $multiSelect = false): mixed
    {
        return $this->style->choice($question, $choices, $default, $multiSelect);
    }

    public function progressStart(int $max = 0): void
    {
        $this->style->progressStart($max);
    }

    public function progressAdvance(int $step = 1): void
    {
        $this->style->progressAdvance($step);
    }

    public function progressFinish(): void
    {
        $this->style->progressFinish();
    }

    public function createProgressBar(int $max = 0): ProgressBar
    {
        return $this->style->createProgressBar($max);
    }
}
