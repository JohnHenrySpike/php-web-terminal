<?php

declare(strict_types=1);

namespace SpikeTerminal\Application;

use SpikeTerminal\Domain\CommandInterface;

interface CommandRegistryInterface
{
    public function register(CommandInterface $command): void;
    public function has(string $name): bool;
    public function get(string $name): ?CommandInterface;
    public function list(): array;
}