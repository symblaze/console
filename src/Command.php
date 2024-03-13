<?php

declare(strict_types=1);

namespace Symblaze\Console;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

abstract class Command extends SymfonyCommand
{
    protected InputInterface $input;
    protected SymfonyStyle $output;

    private const VERBOSITY_MAP = [
        'v' => OutputInterface::VERBOSITY_VERBOSE,
        'vv' => OutputInterface::VERBOSITY_VERY_VERBOSE,
        'vvv' => OutputInterface::VERBOSITY_DEBUG,
        'quiet' => OutputInterface::VERBOSITY_QUIET,
        'normal' => OutputInterface::VERBOSITY_NORMAL,
    ];

    protected function configure(): void
    {
        [$name, $arguments, $options] = Parser::parse(static::getDefaultName());

        $this->setName($name);
        $this->getDefinition()->addArguments($arguments);
        $this->getDefinition()->addOptions($options);
    }

    public function run(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = StyleFactory::create($input, $output);

        return parent::run($input, $output);
    }

    /**
     * Determine if the given argument is present.
     */
    protected function hasArgument($name): bool
    {
        return $this->input->hasArgument($name) && ! is_null($this->argument($name));
    }

    /**
     * Determine if the given option is present.
     */
    protected function hasOption($name): bool
    {
        return $this->input->hasOption($name) && ! is_null($this->option($name));
    }

    protected function option(string $key): bool|array|string|null
    {
        return $this->input->getOption($key);
    }

    protected function options(): array
    {
        return $this->input->getOptions();
    }

    /**
     * Get the value of a command argument.
     */
    protected function argument(string $key): bool|array|string|null
    {
        return $this->input->getArgument($key);
    }

    /**
     * Get all the arguments passed to the command.
     */
    protected function arguments(): array
    {
        return $this->input->getArguments();
    }

    /**
     * Writes a message to the output and adds a newline at the end.
     */
    protected function line(string $message, ?string $style = null, string|int $verbosity = 'normal'): void
    {
        $styled = $style ? "<$style>$message</$style>" : $message;

        $this->output->writeln($styled, $this->parseVerbosity($verbosity));
    }

    protected function info(string|array $message): void
    {
        $this->output->info($message);
    }

    protected function comment(string|array $message): void
    {
        $this->output->comment($message);
    }

    protected function question(string|array $message): void
    {
        $this->line($message, 'question');
    }

    protected function error(string|array $message): void
    {
        $this->output->error($message);
    }

    protected function warning(string|array $message): void
    {
        $this->output->warning($message);
    }

    protected function success(string|array $message): void
    {
        $this->output->success($message);
    }

    protected function title(string $message): void
    {
        $this->output->title($message);
    }

    protected function section(string $message): void
    {
        $this->output->section($message);
    }

    protected function text(string|array $message): void
    {
        $this->output->text($message);
    }

    protected function listing(array $elements): void
    {
        $this->output->listing($elements);
    }

    protected function table(array $headers, array $rows): void
    {
        $this->output->table($headers, $rows);
    }

    protected function horizontalTable(array $headers, array $rows): void
    {
        $this->output->horizontalTable($headers, $rows);
    }

    protected function definitionList(string|array|TableSeparator ...$list): void
    {
        $this->output->definitionList(...$list);
    }

    protected function note(string|array $message): void
    {
        $this->output->note($message);
    }

    protected function caution(string|array $message): void
    {
        $this->output->caution($message);
    }

    protected function ask(string $question, ?string $default = null, ?callable $validator = null): mixed
    {
        return $this->output->ask($question, $default, $validator);
    }

    protected function askHidden(string $question, ?callable $validator = null): mixed
    {
        return $this->output->askHidden($question, $validator);
    }

    protected function confirm(string $question, bool $default = true): bool
    {
        return $this->output->confirm($question, $default);
    }

    protected function choice(string $question, array $choices, mixed $default = null, bool $multiSelect = false): mixed
    {
        return $this->output->choice($question, $choices, $default, $multiSelect);
    }

    protected function progressStart(int $max = 0): void
    {
        $this->output->progressStart($max);
    }

    protected function progressAdvance(int $step = 1): void
    {
        $this->output->progressAdvance($step);
    }

    protected function progressFinish(): void
    {
        $this->output->progressFinish();
    }

    protected function createProgressBar(int $max = 0): ProgressBar
    {
        return $this->output->createProgressBar($max);
    }

    private function parseVerbosity(int|string $level): int
    {
        if (is_int($level)) {
            return $level;
        }

        return self::VERBOSITY_MAP[$level] ?? OutputInterface::VERBOSITY_NORMAL;
    }
}
