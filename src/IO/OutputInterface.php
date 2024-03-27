<?php

declare(strict_types=1);

namespace Symblaze\Console\IO;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\TableSeparator;

interface OutputInterface
{
    public function line(string $message): void;

    public function info(string $message): void;

    public function comment(string $message): void;

    public function error(string $message): void;

    public function warning(string $message): void;

    public function success(string $message): void;

    public function title(string $message): void;

    public function section(string $message): void;

    public function text(string $message): void;

    public function listing(array $elements): void;

    public function table(array $headers, array $rows): void;

    public function horizontalTable(array $headers, array $rows): void;

    public function definitionList(string|array|TableSeparator ...$list): void;

    public function note(string $message): void;

    public function caution(string $message): void;

    public function ask(string $question, ?string $default = null, ?callable $validator = null): mixed;

    public function askHidden(string $question, ?callable $validator = null): mixed;

    public function confirm(string $question, bool $default = true): bool;

    public function choice(string $question, array $choices, mixed $default = null, bool $multiSelect = false): mixed;

    public function progressStart(int $max = 0): void;

    public function progressAdvance(int $step = 1): void;

    public function progressFinish(): void;

    public function createProgressBar(int $max = 0): ProgressBar;
}
