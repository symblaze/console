<?php

declare(strict_types=1);

namespace Symblaze\Console;

use InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * @internal
 */
final class Parser
{
    /**
     * Parse the given console command definition into an array.
     */
    public static function parse(string $expression): array
    {
        $name = self::name($expression);

        if (preg_match_all('/\{\s*(.*?)\s*}/', $expression, $matches) && count($matches[1])) {
            return array_merge([$name], self::parameters($matches[1]));
        }

        return [$name, [], []];
    }

    /**
     * Extract the name of the command from the expression.
     */
    private static function name(string $expression): string
    {
        if (! preg_match('/\S+/', $expression, $matches)) {
            throw new InvalidArgumentException('Unable to determine command name from signature.');
        }

        return $matches[0];
    }

    /**
     * Extract all parameters from the tokens.
     */
    private static function parameters(array $tokens): array
    {
        $arguments = [];

        $options = [];

        foreach ($tokens as $token) {
            if (preg_match('/^-{2,}(.*)/', $token, $matches)) {
                $options[] = self::parseOption($matches[1]);
            } else {
                $arguments[] = self::parseArgument($token);
            }
        }

        return [$arguments, $options];
    }

    /**
     * Parse an argument expression.
     */
    private static function parseArgument(string $token): InputArgument
    {
        [$token, $description] = self::extractDescription($token);

        if (str_ends_with($token, '?*')) {
            return new InputArgument(trim($token, '?*'), InputArgument::IS_ARRAY, $description);
        }

        if (str_ends_with($token, '*')) {
            return new InputArgument(
                trim($token, '*'),
                InputArgument::IS_ARRAY | InputArgument::REQUIRED,
                $description
            );
        }

        if (str_ends_with($token, '?')) {
            return new InputArgument(trim($token, '?'), InputArgument::OPTIONAL, $description);
        }

        if (preg_match('/(.+)=\*(.+)/', $token, $matches)) {
            return new InputArgument(
                $matches[1],
                InputArgument::IS_ARRAY,
                $description,
                preg_split('/,\s?/', $matches[2])
            );
        }

        if (preg_match('/(.+)=(.+)/', $token, $matches)) {
            return new InputArgument($matches[1], InputArgument::OPTIONAL, $description, $matches[2]);
        }

        return new InputArgument($token, InputArgument::REQUIRED, $description);
    }

    /**
     * Parse an option expression.
     */
    private static function parseOption(string $token): InputOption
    {
        [$token, $description] = self::extractDescription($token);

        $matches = preg_split('/\s*\|\s*/', $token, 2);

        $shortcut = null;

        if (isset($matches[1])) {
            [$shortcut, $token] = $matches;
        }

        if (str_ends_with($token, '=')) {
            return new InputOption(trim($token, '='), $shortcut, InputOption::VALUE_OPTIONAL, $description);
        }

        if (str_ends_with($token, '=*')) {
            return new InputOption(
                trim($token, '=*'),
                $shortcut,
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                $description
            );
        }

        if (preg_match('/(.+)=\*(.+)/', $token, $matches)) {
            return new InputOption(
                $matches[1],
                $shortcut,
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                $description,
                preg_split('/,\s?/', $matches[2])
            );
        }

        if (preg_match('/(.+)=(.+)/', $token, $matches)) {
            return new InputOption($matches[1], $shortcut, InputOption::VALUE_OPTIONAL, $description, $matches[2]);
        }

        return new InputOption($token, $shortcut, InputOption::VALUE_NONE, $description);
    }

    /**
     * Parse the token into its token and description segments.
     */
    private static function extractDescription(string $token): array
    {
        $parts = preg_split('/\s+:\s+/', trim($token), 2);

        return 2 === count($parts) ? $parts : [$token, ''];
    }
}
