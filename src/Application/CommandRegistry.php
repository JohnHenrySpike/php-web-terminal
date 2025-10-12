<?php
declare(strict_types=1);

namespace SpikeTerminal\Application;

use LogicException;
use SpikeTerminal\Commands\DateCommand;
use SpikeTerminal\Commands\HelpCommand;
use SpikeTerminal\Commands\ServerCommand;
use SpikeTerminal\Domain\CommandInterface;

class CommandRegistry implements CommandRegistryInterface
{
    /** @var array<string,CommandInterface> */
    private array $commands = [];

    public function __construct() {
        $this->register(new HelpCommand($this));
        $this->register(new DateCommand());
        $this->register(new ServerCommand());
    }

    public function register(CommandInterface $command): void
    {
        if ($this->has($command::name())) {
            throw new LogicException("Command {$command::name()} already registered");
        }
        $this->commands[$command::name()] = $command;
    }

    public function has(string $name): bool
    {
        return isset($this->commands[$name]);
    }

    public function get(string $name): ?CommandInterface
    {
        return $this->commands[$name] ?? null;
    }

    public function list(): array
    {
        return $this->commands;
    }
}
