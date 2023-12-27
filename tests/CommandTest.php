<?php

declare(strict_types=1);

namespace Symblaze\Console\Tests;

use Symblaze\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

final class CommandTest extends TestCase
{
    /** @test */
    public function command_name(): void
    {
        $command = new MyCommand();

        $this->assertSame('acme:command', $command->getName());
    }

    /** @test */
    public function it_parses_required_arguments(): void
    {
        $command = new MyCommand();

        $this->assertTrue($command->getDefinition()->hasArgument('required_argument'));
        $this->assertTrue($command->getDefinition()->getArgument('required_argument')->isRequired());
    }

    /** @test */
    public function it_parses_optional_arguments(): void
    {
        $command = new MyCommand();

        $this->assertTrue($command->getDefinition()->hasArgument('optional_argument'));
        $this->assertFalse($command->getDefinition()->getArgument('optional_argument')->isRequired());
    }

    /** @test */
    public function it_parses_optional_argument_with_default_value(): void
    {
        $command = new MyCommand();

        $this->assertTrue($command->getDefinition()->hasArgument('argument_with_value'));
        $this->assertFalse($command->getDefinition()->getArgument('argument_with_value')->isRequired());
        $this->assertSame('default', $command->getDefinition()->getArgument('argument_with_value')->getDefault());
    }

    /** @test */
    public function it_parses_options(): void
    {
        $command = new MyCommand();

        $this->assertTrue($command->getDefinition()->hasOption('option'));
        $this->assertFalse($command->getDefinition()->getOption('option')->isValueRequired());
        $this->assertFalse($command->getDefinition()->getOption('option')->getDefault());
    }

    /** @test */
    public function it_parses_option_that_requires_value(): void
    {
        $command = new MyCommand();

        $this->assertTrue($command->getDefinition()->hasOption('option_with_value'));
        $this->assertFalse($command->getDefinition()->getOption('option_with_value')->isValueRequired());
        $this->assertNull($command->getDefinition()->getOption('option_with_value')->getDefault());
    }

    /** @test */
    public function it_parses_option_with_default_value(): void
    {
        $command = new MyCommand();

        $this->assertTrue($command->getDefinition()->hasOption('option_with_default'));
        $this->assertFalse($command->getDefinition()->getOption('option_with_default')->isValueRequired());
        $this->assertSame('default', $command->getDefinition()->getOption('option_with_default')->getDefault());
    }

    /** @test */
    public function it_parses_option_shortcut(): void
    {
        $command = new MyCommand();

        $this->assertTrue($command->getDefinition()->hasOption('option'));
        $this->assertSame('O', $command->getDefinition()->getOption('option')->getShortcut());
        $this->assertSame('OWV', $command->getDefinition()->getOption('option_with_value')->getShortcut());
        $this->assertSame('OWDV', $command->getDefinition()->getOption('option_with_default')->getShortcut());
    }

    /** @test */
    public function it_parses_argument_arrays(): void
    {
        $command = new CommandWithArrays();

        $this->assertTrue($command->getDefinition()->hasArgument('argument_array'));
        $this->assertTrue($command->getDefinition()->getArgument('argument_array')->isArray());
    }

    /** @test */
    public function it_parses_option_arrays(): void
    {
        $command = new CommandWithArrays();

        $this->assertTrue($command->getDefinition()->hasOption('option_array'));
        $this->assertTrue($command->getDefinition()->getOption('option_array')->isArray());
    }

    /** @test */
    public function it_parses_argument_array_that_accepts_zero_or_more_items(): void
    {
        $command = new CommandWithOptionalArgumentArray();

        $this->assertTrue($command->getDefinition()->hasArgument('optional_argument_list'));
        $this->assertTrue($command->getDefinition()->getArgument('optional_argument_list')->isArray());
    }

    /** @test */
    public function it_parses_argument_description(): void
    {
        $command = new CommandWithDescriptions();

        $this->assertTrue($command->getDefinition()->hasArgument('argument'));
        $this->assertSame('Argument description', $command->getDefinition()->getArgument('argument')->getDescription());
    }

    /** @test */
    public function it_parses_option_description(): void
    {
        $command = new CommandWithDescriptions();

        $this->assertTrue($command->getDefinition()->hasOption('option'));
        $this->assertSame('Option description', $command->getDefinition()->getOption('option')->getDescription());
    }
}

#[AsCommand(name: 'acme:command {required_argument} {optional_argument?} {argument_with_value=default} {--O|option} {--OWV|option_with_value=} {--OWDV|option_with_default=default}')]
class MyCommand extends Command
{
}

#[AsCommand(name: 'acme:array:command {argument_array*} {--option_array=*}')]
class CommandWithArrays extends Command
{

}

#[AsCommand(name: 'acme:optional:command {optional_argument_list?*}')]
class CommandWithOptionalArgumentArray extends Command
{
}

#[AsCommand(name: 'acme:descriptions:command {argument : Argument description} {--O|option : Option description}')]
class CommandWithDescriptions extends Command
{
}