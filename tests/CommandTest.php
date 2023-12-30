<?php

declare(strict_types=1);

namespace Symblaze\Console\Tests;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CommandTest extends TestCase
{
    /** @test */
    public function command_name(): void
    {
        $command = new Doubles\MyCommand();

        $this->assertSame('acme:command', $command->getName());
    }

    /** @test */
    public function it_parses_required_arguments(): void
    {
        $command = new Doubles\MyCommand();

        $this->assertTrue($command->getDefinition()->hasArgument('required_argument'));
        $this->assertTrue($command->getDefinition()->getArgument('required_argument')->isRequired());
    }

    /** @test */
    public function it_parses_optional_arguments(): void
    {
        $command = new Doubles\MyCommand();

        $this->assertTrue($command->getDefinition()->hasArgument('optional_argument'));
        $this->assertFalse($command->getDefinition()->getArgument('optional_argument')->isRequired());
    }

    /** @test */
    public function it_parses_optional_argument_with_default_value(): void
    {
        $command = new Doubles\MyCommand();

        $this->assertTrue($command->getDefinition()->hasArgument('argument_with_value'));
        $this->assertFalse($command->getDefinition()->getArgument('argument_with_value')->isRequired());
        $this->assertSame('default', $command->getDefinition()->getArgument('argument_with_value')->getDefault());
    }

    /** @test */
    public function it_parses_options(): void
    {
        $command = new Doubles\MyCommand();

        $this->assertTrue($command->getDefinition()->hasOption('option'));
        $this->assertFalse($command->getDefinition()->getOption('option')->isValueRequired());
        $this->assertFalse($command->getDefinition()->getOption('option')->getDefault());
    }

    /** @test */
    public function it_parses_option_that_requires_value(): void
    {
        $command = new Doubles\MyCommand();

        $this->assertTrue($command->getDefinition()->hasOption('option_with_value'));
        $this->assertFalse($command->getDefinition()->getOption('option_with_value')->isValueRequired());
        $this->assertNull($command->getDefinition()->getOption('option_with_value')->getDefault());
    }

    /** @test */
    public function it_parses_option_with_default_value(): void
    {
        $command = new Doubles\MyCommand();

        $this->assertTrue($command->getDefinition()->hasOption('option_with_default'));
        $this->assertFalse($command->getDefinition()->getOption('option_with_default')->isValueRequired());
        $this->assertSame('default', $command->getDefinition()->getOption('option_with_default')->getDefault());
    }

    /** @test */
    public function it_parses_option_shortcut(): void
    {
        $command = new Doubles\MyCommand();

        $this->assertTrue($command->getDefinition()->hasOption('option'));
        $this->assertSame('O', $command->getDefinition()->getOption('option')->getShortcut());
        $this->assertSame('OWV', $command->getDefinition()->getOption('option_with_value')->getShortcut());
        $this->assertSame('OWDV', $command->getDefinition()->getOption('option_with_default')->getShortcut());
    }

    /** @test */
    public function it_parses_argument_arrays(): void
    {
        $command = new Doubles\CommandWithArrays();

        $this->assertTrue($command->getDefinition()->hasArgument('argument_array'));
        $this->assertTrue($command->getDefinition()->getArgument('argument_array')->isArray());
    }

    /** @test */
    public function it_parses_option_arrays(): void
    {
        $command = new Doubles\CommandWithArrays();

        $this->assertTrue($command->getDefinition()->hasOption('option_array'));
        $this->assertTrue($command->getDefinition()->getOption('option_array')->isArray());
    }

    /** @test */
    public function it_parses_argument_array_that_accepts_zero_or_more_items(): void
    {
        $command = new Doubles\CommandWithOptionalArgumentArray();

        $this->assertTrue($command->getDefinition()->hasArgument('optional_argument_list'));
        $this->assertTrue($command->getDefinition()->getArgument('optional_argument_list')->isArray());
    }

    /** @test */
    public function it_parses_argument_description(): void
    {
        $command = new Doubles\CommandWithDescriptions();

        $this->assertTrue($command->getDefinition()->hasArgument('argument'));
        $this->assertSame('Argument description', $command->getDefinition()->getArgument('argument')->getDescription());
    }

    /** @test */
    public function it_parses_option_description(): void
    {
        $command = new Doubles\CommandWithDescriptions();

        $this->assertTrue($command->getDefinition()->hasOption('option'));
        $this->assertSame('Option description', $command->getDefinition()->getOption('option')->getDescription());
    }

    /** @test */
    public function determine_if_an_argument_is_present(): void
    {
        $command = new Doubles\MyCommand();
        $this->executeCommand($command, ['required_argument' => 'value']);

        $this->assertTrue($command->hasArgument('required_argument'));
        $this->assertFalse($command->hasArgument('optional_argument'));
    }

    /** @test */
    public function get_value_of_an_argument(): void
    {
        $command = new Doubles\MyCommand();
        $this->executeCommand($command, ['required_argument' => 'value']);

        $this->assertSame('value', $command->argument('required_argument'));
        $this->assertNull($command->argument('optional_argument'));
        $this->assertSame('default', $command->argument('argument_with_value'));
    }

    /** @test */
    public function get_all_arguments(): void
    {
        $command = new Doubles\MyCommand();
        $this->executeCommand($command, ['required_argument' => 'value']);

        $expected = ['required_argument' => 'value', 'optional_argument' => null, 'argument_with_value' => 'default'];
        $this->assertSame($expected, $command->arguments());
    }

    /** @test */
    public function determine_if_an_option_is_present(): void
    {
        $command = new Doubles\MyCommand();
        $this->executeCommand($command, ['required_argument' => 'value', '--option' => true]);

        $this->assertTrue($command->hasOption('option'));
        $this->assertFalse($command->hasOption('option_with_value'));
        $this->assertTrue($command->hasOption('option_with_default'));
    }

    /** @test */
    public function get_value_of_an_option(): void
    {
        $command = new Doubles\MyCommand();
        $this->executeCommand($command, ['required_argument' => 'value', '--option' => true]);

        $this->assertTrue($command->option('option'));
        $this->assertNull($command->option('option_with_value'));
        $this->assertSame('default', $command->option('option_with_default'));
    }

    /** @test */
    public function get_all_options(): void
    {
        $command = new Doubles\MyCommand();
        $this->executeCommand($command, ['required_argument' => 'value', '--option' => true]);

        $expected = ['option' => true, 'option_with_value' => null, 'option_with_default' => 'default'];
        $this->assertSame($expected, $command->options());
    }

    /** @test */
    public function write_message_with_line(): void
    {
        $command = new Doubles\MyCommand();
        $command->setInput($this->createMock(InputInterface::class));
        $outputMock = $this->createMock(OutputInterface::class);
        $command->setOutput($outputMock);

        $outputMock->expects($this->once())->method('writeln')->with('Hello world');

        $command->line('Hello world');
    }

    /** @test */
    public function write_styled_message_with_line(): void
    {
        $command = new Doubles\MyCommand();
        $command->setInput($this->createMock(InputInterface::class));
        $outputMock = $this->createMock(OutputInterface::class);
        $command->setOutput($outputMock);

        $outputMock->expects($this->once())->method('writeln')->with('<info>Hello world</info>');

        $command->line('Hello world', 'info');
    }

    /** @test */
    public function write_a_verbose_message_with_line(): void
    {
        $command = new Doubles\MyCommand();
        $command->setInput($this->createMock(InputInterface::class));
        $outputMock = $this->createMock(OutputInterface::class);
        $command->setOutput($outputMock);

        $outputMock->expects($this->once())->method('writeln')->with('Hello world', OutputInterface::VERBOSITY_DEBUG);

        $command->line('Hello world', null, 'vvv');
    }

    /** @test */
    public function write_an_info_message(): void
    {
        $command = new Doubles\MyCommand();
        $command->setInput($this->createMock(InputInterface::class));
        $outputMock = $this->createMock(OutputInterface::class);
        $command->setOutput($outputMock);

        $outputMock->expects($this->once())->method('writeln')->with('<info>Hello world</info>');

        $command->info('Hello world');
    }

    /** @test */
    public function write_a_comment_message(): void
    {
        $command = new Doubles\MyCommand();
        $command->setInput($this->createMock(InputInterface::class));
        $outputMock = $this->createMock(OutputInterface::class);
        $command->setOutput($outputMock);

        $outputMock->expects($this->once())->method('writeln')->with('<comment>Hello world</comment>');

        $command->comment('Hello world');
    }

    /** @test */
    public function write_a_question(): void
    {
        $command = new Doubles\MyCommand();
        $command->setInput($this->createMock(InputInterface::class));
        $outputMock = $this->createMock(OutputInterface::class);
        $command->setOutput($outputMock);

        $outputMock->expects($this->once())->method('writeln')->with('<question>Hello world</question>');

        $command->question('Hello world');
    }

    /** @test */
    public function write_an_error_message(): void
    {
        $command = new Doubles\MyCommand();
        $command->setInput($this->createMock(InputInterface::class));
        $outputMock = $this->createMock(OutputInterface::class);
        $command->setOutput($outputMock);

        $outputMock->expects($this->once())->method('writeln')->with('<error>Hello world</error>');

        $command->error('Hello world');
    }

    /** @test */
    public function write_a_warn_message(): void
    {
        $command = new Doubles\MyCommand();
        $command->setInput($this->createMock(InputInterface::class));
        $outputMock = $this->createMock(OutputInterface::class);
        $command->setOutput($outputMock);

        $outputMock->expects($this->once())->method('writeln')->with('<comment>Hello world</comment>');

        $command->warn('Hello world');
    }

    /** @test */
    public function write_a_success_message(): void
    {
        $command = new Doubles\MyCommand();
        $command->setInput($this->createMock(InputInterface::class));
        $outputMock = $this->createMock(OutputInterface::class);
        $command->setOutput($outputMock);

        $outputMock->expects($this->once())->method('writeln')->with('<info>Hello world</info>');

        $command->success('Hello world');
    }
}
