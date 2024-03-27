<?php

declare(strict_types=1);

namespace Symblaze\Console\Tests;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

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
        $outputMock = $this->createMock(SymfonyStyle::class);
        $command->setOutput($outputMock);

        $outputMock->expects($this->once())->method('writeln')->with('Hello world');

        $command->line('Hello world');
    }

    /** @test */
    public function write_styled_message_with_line(): void
    {
        $command = new Doubles\MyCommand();
        $command->setInput($this->createMock(InputInterface::class));
        $outputMock = $this->createMock(SymfonyStyle::class);
        $command->setOutput($outputMock);

        $outputMock->expects($this->once())->method('writeln')->with('<info>Hello world</info>');

        $command->line('Hello world', 'info');
    }

    /** @test */
    public function write_an_info_message(): void
    {
        $command = new Doubles\MyCommand();
        $command->setInput($this->createMock(InputInterface::class));
        $outputMock = $this->createMock(SymfonyStyle::class);
        $command->setOutput($outputMock);

        $outputMock->expects($this->once())->method('info')->with('Hello world');

        $command->info('Hello world');
    }

    /** @test */
    public function write_a_comment_message(): void
    {
        $command = new Doubles\MyCommand();
        $command->setInput($this->createMock(InputInterface::class));
        $outputMock = $this->createMock(SymfonyStyle::class);
        $command->setOutput($outputMock);

        $outputMock->expects($this->once())->method('comment')->with('Hello world');

        $command->comment('Hello world');
    }

    /** @test */
    public function write_a_question(): void
    {
        $command = new Doubles\MyCommand();
        $command->setInput($this->createMock(InputInterface::class));
        $outputMock = $this->createMock(SymfonyStyle::class);
        $command->setOutput($outputMock);

        $outputMock->expects($this->once())->method('writeln')->with('<question>Hello world</question>');

        $command->question('Hello world');
    }

    /** @test */
    public function write_an_error_message(): void
    {
        $command = new Doubles\MyCommand();
        $command->setInput($this->createMock(InputInterface::class));
        $outputMock = $this->createMock(SymfonyStyle::class);
        $command->setOutput($outputMock);

        $outputMock->expects($this->once())->method('error')->with('Hello world');

        $command->error('Hello world');
    }

    /** @test */
    public function write_a_warn_message(): void
    {
        $command = new Doubles\MyCommand();
        $command->setInput($this->createMock(InputInterface::class));
        $outputMock = $this->createMock(SymfonyStyle::class);
        $command->setOutput($outputMock);

        $outputMock->expects($this->once())->method('warning')->with('Hello world');

        $command->warning('Hello world');
    }

    /** @test */
    public function write_a_success_message(): void
    {
        $command = new Doubles\MyCommand();
        $command->setInput($this->createMock(InputInterface::class));
        $outputMock = $this->createMock(SymfonyStyle::class);
        $command->setOutput($outputMock);

        $outputMock->expects($this->once())->method('success')->with('Hello world');

        $command->success('Hello world');
    }

    /** @test */
    public function write_a_title(): void
    {
        $command = new Doubles\MyCommand();
        $command->setInput($this->createMock(InputInterface::class));
        $outputMock = $this->createMock(SymfonyStyle::class);
        $command->setOutput($outputMock);

        $outputMock->expects($this->once())->method('title')->with('Hello world');

        $command->title('Hello world');
    }

    /** @test */
    public function display_a_section(): void
    {
        $command = new Doubles\MyCommand();
        $command->setInput($this->createMock(InputInterface::class));
        $outputMock = $this->createMock(SymfonyStyle::class);
        $command->setOutput($outputMock);

        $outputMock->expects($this->once())->method('section')->with('Hello world');

        $command->section('Hello world');
    }

    /** @test */
    public function display_a_single_text_message(): void
    {
        $command = new Doubles\MyCommand();
        $command->setInput($this->createMock(InputInterface::class));
        $outputMock = $this->createMock(SymfonyStyle::class);
        $command->setOutput($outputMock);

        $outputMock->expects($this->once())->method('text')->with('Hello world');

        $command->text('Hello world');
    }

    /** @test */
    public function display_un_ordered_list(): void
    {
        $command = new Doubles\MyCommand();
        $command->setInput($this->createMock(InputInterface::class));
        $outputMock = $this->createMock(SymfonyStyle::class);
        $command->setOutput($outputMock);

        $outputMock->expects($this->once())->method('listing')->with(['Hello world', 'Hello world']);

        $command->listing(['Hello world', 'Hello world']);
    }

    /** @test */
    public function display_a_table(): void
    {
        $headers = ['Header 1', 'Header 2'];
        $rows = [
            ['Cell 1-1', 'Cell 1-2'],
            ['Cell 2-1', 'Cell 2-2'],
            ['Cell 3-1', 'Cell 3-2'],
        ];
        $command = new Doubles\MyCommand();
        $command->setInput($this->createMock(InputInterface::class));
        $outputMock = $this->createMock(SymfonyStyle::class);
        $command->setOutput($outputMock);

        $outputMock->expects($this->once())->method('table')->with($headers, $rows);

        $command->table($headers, $rows);
    }

    /** @test */
    public function display_horizontal_table(): void
    {
        $headers = ['Header 1', 'Header 2'];
        $rows = [
            ['Cell 1-1', 'Cell 1-2'],
            ['Cell 2-1', 'Cell 2-2'],
            ['Cell 3-1', 'Cell 3-2'],
        ];
        $command = new Doubles\MyCommand();
        $command->setInput($this->createMock(InputInterface::class));
        $outputMock = $this->createMock(SymfonyStyle::class);
        $command->setOutput($outputMock);

        $outputMock->expects($this->once())->method('horizontalTable')->with($headers, $rows);

        $command->horizontalTable($headers, $rows);
    }

    /** @test */
    public function display_a_definition_list(): void
    {
        $list = [
            'This is a title',
            ['foo1' => 'bar1'],
            ['foo2' => 'bar2'],
            ['foo3' => 'bar3'],
            new TableSeparator(),
            'This is another title',
            ['foo4' => 'bar4'],
        ];
        $command = new Doubles\MyCommand();
        $command->setInput($this->createMock(InputInterface::class));
        $outputMock = $this->createMock(SymfonyStyle::class);
        $command->setOutput($outputMock);

        $outputMock->expects($this->once())->method('definitionList')->with($list);

        $command->definitionList($list);
    }

    /** @test */
    public function display_a_note(): void
    {
        $command = new Doubles\MyCommand();
        $command->setInput($this->createMock(InputInterface::class));
        $outputMock = $this->createMock(SymfonyStyle::class);
        $command->setOutput($outputMock);

        $outputMock->expects($this->once())->method('note')->with('Hello world');

        $command->note('Hello world');
    }

    /** @test */
    public function display_a_caution(): void
    {
        $command = new Doubles\MyCommand();
        $command->setInput($this->createMock(InputInterface::class));
        $outputMock = $this->createMock(SymfonyStyle::class);
        $command->setOutput($outputMock);

        $outputMock->expects($this->once())->method('caution')->with('Hello world');

        $command->caution('Hello world');
    }

    /** @test */
    public function ask_the_user_to_provide_a_value(): void
    {
        $question = 'What is your name?';
        $default = 'John Doe';
        $command = new Doubles\MyCommand();
        $command->setInput($this->createMock(InputInterface::class));
        $outputMock = $this->createMock(SymfonyStyle::class);
        $command->setOutput($outputMock);

        $outputMock->expects($this->once())->method('ask')->with($question, $default)->willReturn($default);

        $this->assertSame('John Doe', $command->ask($question, $default));
    }

    /** @test */
    public function ask_the_user_for_sensitive_data(): void
    {
        $question = 'What is your password?';
        $command = new Doubles\MyCommand();
        $command->setInput($this->createMock(InputInterface::class));
        $outputMock = $this->createMock(SymfonyStyle::class);
        $command->setOutput($outputMock);

        $outputMock->expects($this->once())->method('askHidden')->with($question)->willReturn('secret');

        $this->assertSame('secret', $command->askHidden($question));
    }

    /** @test */
    public function ask_a_yes_or_no_question(): void
    {
        $question = 'Do you want to continue?';
        $command = new Doubles\MyCommand();
        $command->setInput($this->createMock(InputInterface::class));
        $output = $this->createMock(SymfonyStyle::class);
        $command->setOutput($output);

        $output->expects($this->once())->method('confirm')->with($question)->willReturn(true);

        $this->assertTrue($command->confirm($question));
    }

    /** @test */
    public function ask_a_question_whose_answer_is_constrained_to_a_given_list(): void
    {
        $question = 'Select the queue to analyze';
        $choices = ['queue1', 'queue2', 'queue3'];
        $command = new Doubles\MyCommand();
        $command->setInput($this->createMock(InputInterface::class));
        $output = $this->createMock(SymfonyStyle::class);
        $command->setOutput($output);

        $output->expects($this->once())->method('choice')->with($question, $choices)->willReturn('queue1');

        $this->assertSame('queue1', $command->choice($question, $choices));
    }

    /** @test */
    public function progress_bar_start(): void
    {
        $command = new Doubles\MyCommand();
        $command->setInput($this->createMock(InputInterface::class));
        $output = $this->createMock(SymfonyStyle::class);
        $command->setOutput($output);

        $output->expects($this->once())->method('progressStart')->with(10);

        $command->progressStart(10);
    }

    /** @test */
    public function progress_advance(): void
    {
        $command = new Doubles\MyCommand();
        $command->setInput($this->createMock(InputInterface::class));
        $output = $this->createMock(SymfonyStyle::class);
        $command->setOutput($output);

        $output->expects($this->once())->method('progressAdvance')->with(10);

        $command->progressAdvance(10);
    }

    /** @test */
    public function progress_finish(): void
    {
        $command = new Doubles\MyCommand();
        $command->setInput($this->createMock(InputInterface::class));
        $output = $this->createMock(SymfonyStyle::class);
        $command->setOutput($output);

        $output->expects($this->once())->method('progressFinish');

        $command->progressFinish();
    }

    /** @test */
    public function create_progress_bar(): void
    {
        $command = new Doubles\MyCommand();
        $command->setInput($this->createMock(InputInterface::class));
        $output = $this->createMock(SymfonyStyle::class);
        $command->setOutput($output);

        $output->expects($this->once())->method('createProgressBar')->with(10)->willReturn(new ProgressBar($output));

        $command->createProgressBar(10);
    }
}
