# Upgrade from 1.x to 2.0

## High impact changes

- Replace direct usage out output helpers with a call to the `$this->outoput->methodName()`.

```diff
// Instead of
- $this->info('Hello world'); // A single example, but all output helpers are affected

// Use
+ $this->output->info('Hello world');
```

## Medium impact changes

## Low impact changes
