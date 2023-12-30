<?php

declare(strict_types=1);

namespace Symblaze\Console\Tests\Doubles;

use Symblaze\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @method hasArgument(string $name): bool
 * @method argument(string $key): bool|array|string|null
 * @method arguments(): array
 * @method hasOption(string $name): bool
 * @method option(string $key): bool|array|string|null
 * @method options(): array
 * @method line(string $message, ?string $style = null, string|int $verbosity = 'normal'): void
 * @method info(string|array $message): void
 * @method comment(string|array $message): void
 * @method question(string|array $message): void
 * @method error(string|array $message): void
 * @method warning(string|array $message): void
 * @method success(string|array $message): void
 * @method title(string $message): void
 * @method section(string $message): void
 * @method text(string|array $message): void
 * @method listing(array $elements): void
 * @method table(array $headers, array $rows): void
 * @method horizontalTable(array $headers, array $rows): void
 * @method definitionList(string|array|TableSeparator ...$list): void
 * @method note(string|array $message): void
 * @method caution(string|array $message): void
 * @method ask(string $question, string $default = null, callable $validator = null): mixed
 * @method askHidden(string $question, callable $validator = null): mixed
 * @method confirm(string $question, bool $default = true): bool
 * @method choice(string $question, array $choices, mixed $default = null, bool $multiSelect = false): mixed
 */
#[AsCommand(name: 'acme:command {required_argument} {optional_argument?} {argument_with_value=default} {--O|option} {--OWV|option_with_value=} {--OWDV|option_with_default=default}')]
class MyCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return self::SUCCESS;
    }

    public function __call(string $name, array $arguments)
    {
        return parent::$name(...$arguments);
    }

    public function setOutput(SymfonyStyle $output): void
    {
        $this->output = $output;
    }

    public function setInput(InputInterface $input): void
    {
        $this->input = $input;
    }
}
