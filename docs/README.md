# Documentation

## Creating a command

To create a command, instead of extending the `Command` class from Symfony, you should extend the Symblaze console
command class. Then use the Symfony `AsCommand` attribute to register the command.

```php
use Symblaze\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[ASCommand(name: 'send:email', description: 'Send a marketing email to a user')]
class SendEmailCommand extends Command
{   
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        //...
    }
}
```

## Defining command Inputs

No need to override the `configure` method to define the command arguments. All you need to do is to add the
argument names wrapped in curly braces to the command `name` attribute.

### Arguments

```php
use Symblaze\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[ASCommand(name: 'send:email {user}', description: 'Send a marketing email to a user')]
class SendEmailCommand extends Command
{   
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        //...
    }
}
```

The command above will create a required argument named `user`. You may also make arguments optional or define default
values for arguments:

```php
// Optional argument
'mail:send {user?}'

// Argument with default value
'mail:send {user=imdhemy}'
```

### Options

Options are defined in the same way as arguments, but they are prefixed with two dashes `--`:

```php
AsCommand(name: 'mail:send {user} {--queue}')
```

In the example above, the command will have a required argument named `user` and an option named `queue`.

If the user must provide a value for the option, you can define it as follows:

```php
AsCommand(name: 'mail:send {user} {--queue=}')
```

Or you can define a default value for the option:

```php
AsCommand(name: 'mail:send {user} {--queue=10}')
```

You can also define a shortcut for the option:

```php
AsCommand(name: 'mail:send {user} {--Q|queue}')
```

### Input arrays

If you want to define arguments or options that accept multiple values, you can do so by adding `*` after the argument
or option name:

```php
AsCommand(name: 'mail:send {user*}')
```

You can combine the `?` and `*` characters to define an optional array:

```php
AsCommand(name: 'mail:send {user?*}')
```

The same applies to options:

```php
AsCommand(name: 'mail:send {user} {--queue*}')
```

### Input descriptions

You can define a description for each argument or option by adding a `:` after the argument or option name:

```php
AsCommand(name: 'mail:send {user : The user ID} {--queue : The queue ID}')
```

## Command Input Output

### Retrieve input values

You can retrieve the value of an argument or option using the `argument` and `option` methods:

```php
$user = $this->argument('user');

$queue = $this->option('queue');
```

In case you want to retrieve all the arguments or options, you can use the `arguments` and `options` methods:

```php
$arguments = $this->arguments();

$options = $this->options();
```

### Determine if an input is present

You can determine if an argument or option is present using the `hasArgument` and `hasOption` methods. Both
methods return `true` exists and is not `null`:

```php
if ($this->hasArgument('user')) {
    //...
}

if ($this->hasOption('queue')) {
    //...
}
```

## Writing output

To send output to the console, you can use the `line`, `info`, `warn`, `error` and `success` methods:

```php
$this->line('This is a simple line');
```
