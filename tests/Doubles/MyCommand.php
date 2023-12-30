<?php

declare(strict_types=1);

namespace Symblaze\Console\Tests\Doubles;

use Illuminate\Console\OutputStyle;
use Symblaze\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method hasArgument(string $name): bool
 * @method argument(string $key): bool|array|string|null
 * @method arguments(): array
 * @method hasOption(string $name): bool
 * @method option(string $key): bool|array|string|null
 * @method options(): array
 * @method line(string $message, ?string $style = null, string|int $verbosity = 'normal'): void
 * @method info($string, string|int $verbosity = 'normal'): void
 * @method comment($string, string|int $verbosity = 'normal'): void
 * @method question($string, string|int $verbosity = 'normal'): void
 * @method error($string, string|int $verbosity = 'normal'): void
 * @method warn($string, string|int $verbosity = 'normal'): void
 * @method success($string, string|int $verbosity = 'normal'): void
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

    public function setOutput(OutputInterface $output): void
    {
        $this->output = new OutputStyle($this->input, $output);
    }

    public function setInput(InputInterface $input): void
    {
        $this->input = $input;
    }
}
