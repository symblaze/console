<?php

declare(strict_types=1);

namespace Symblaze\Console\Tests;

use InvalidArgumentException;
use Symblaze\Console\Parser;

final class ParserTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider commandNameProvider
     */
    public function it_parses_command_name(string $name, string $expectedName): void
    {
        [$name] = Parser::parse($name);

        $this->assertSame($expectedName, $name);
    }

    /** @test */
    public function it_requires_valid_name(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unable to determine command name from signature.');

        Parser::parse('');
    }

    /**
     * @test
     */
    public function it_parses_arguments(): void
    {
        $expression = 'acme:command {argument} {optional_argument?} {argument_with_value=default}';

        [$name, $arguments] = Parser::parse($expression);

        $this->assertSame('acme:command', $name);
        $this->assertCount(3, $arguments);
        $this->assertSame('argument', $arguments[0]->getName());
        $this->assertTrue($arguments[0]->isRequired());
        $this->assertSame('optional_argument', $arguments[1]->getName());
        $this->assertFalse($arguments[1]->isRequired());
        $this->assertSame('argument_with_value', $arguments[2]->getName());
        $this->assertFalse($arguments[2]->isRequired());
        $this->assertSame('default', $arguments[2]->getDefault());
    }

    /** @test */
    public function it_parses_options(): void
    {
        $expression = 'acme:command {--option} {--option_with_value=} {--option_with_default=default}';

        [$name, $arguments, $options] = Parser::parse($expression);

        $this->assertSame('acme:command', $name);
        $this->assertCount(0, $arguments);
        $this->assertCount(3, $options);
        $this->assertSame('option', $options[0]->getName());
        $this->assertFalse($options[0]->isValueRequired());
        $this->assertFalse($options[0]->getDefault());
        $this->assertSame('option_with_value', $options[1]->getName());
        $this->assertFalse($options[1]->isValueRequired());
        $this->assertNull($options[1]->getDefault());
        $this->assertSame('option_with_default', $options[2]->getName());
        $this->assertFalse($options[2]->isValueRequired());
        $this->assertSame('default', $options[2]->getDefault());
    }

    /** @test */
    public function it_parses_argument_arrays(): void
    {
        $expression = 'acme:command {argument*}';

        [$name, $arguments] = Parser::parse($expression);

        $this->assertSame('acme:command', $name);
        $this->assertCount(1, $arguments);
        $this->assertSame('argument', $arguments[0]->getName());
        $this->assertTrue($arguments[0]->isArray());
    }

    /** @test */
    public function it_parses_argument_array_with_zero_or_more_items(): void
    {
        $expression = 'acme:command {argument?*}';

        [$name, $arguments] = Parser::parse($expression);

        $this->assertSame('acme:command', $name);
        $this->assertCount(1, $arguments);
        $this->assertSame('argument', $arguments[0]->getName());
        $this->assertTrue($arguments[0]->isArray());
    }

    /** @test */
    public function it_parses_option_arrays(): void
    {
        $expression = 'acme:command {--option=*}';

        [$name, $arguments, $options] = Parser::parse($expression);

        $this->assertSame('acme:command', $name);
        $this->assertCount(0, $arguments);
        $this->assertCount(1, $options);
        $this->assertSame('option', $options[0]->getName());
        $this->assertTrue($options[0]->isArray());
    }

    /** @test */
    public function it_parses_descriptions(): void
    {
        $expression = 'acme:command {argument : Argument description} {--option= : Option description}';

        [$name, $arguments, $options] = Parser::parse($expression);

        $this->assertSame('acme:command', $name);
        $this->assertCount(1, $arguments);
        $this->assertCount(1, $options);
        $this->assertSame('Argument description', $arguments[0]->getDescription());
        $this->assertSame('Option description', $options[0]->getDescription());
    }

    /** @test */
    public function it_parses_option_shortcut(): void
    {
        $expression = 'acme:command {--O|option} {--OWV|option_with_value=} {--OWDV|option_with_default=default}';

        [$name, $arguments, $options] = Parser::parse($expression);

        $this->assertSame('acme:command', $name);
        $this->assertCount(0, $arguments);
        $this->assertCount(3, $options);
        $this->assertSame('O', $options[0]->getShortcut());
        $this->assertSame('OWV', $options[1]->getShortcut());
        $this->assertSame('OWDV', $options[2]->getShortcut());
    }

    public static function commandNameProvider(): array
    {
        return [
            'simple' => ['acme:command', 'acme:command'],
            'with multiple spaces' => ['  acme:command  ', 'acme:command'],
            'argument' => ['acme:command {argument}', 'acme:command'],
            'optional argument' => ['acme:command {argument?}', 'acme:command'],
            'argument that has default value' => ['acme:command {argument=default}', 'acme:command'],
            'option' => ['acme:command {--option}', 'acme:command'],
            'option that requires value' => ['acme:command {--option=}', 'acme:command'],
            'option that has default value' => ['acme:command {--option=default}', 'acme:command'],
            'argument and option' => ['acme:command {argument} {--option}', 'acme:command'],
            'optional argument and option' => ['acme:command {argument?} {--option}', 'acme:command'],
            'argument with default value and option' => ['acme:command {argument=default} {--option}', 'acme:command'],
            'argument array' => ['acme:command {argument*}', 'acme:command'],
            'options array' => ['acme:command {--option=*}', 'acme:command'],
            'argument that has description' => ['acme:command {argument : Description}', 'acme:command'],
            'option that has description' => ['acme:command {--option= : Description}', 'acme:command'],
            'optional argument list' => ['acme:command {argument?*}', 'acme:command'],
        ];
    }
}
