<?php

declare(strict_types=1);

namespace SpikeTerminal\Application;

use JsonSerializable;

class TerminalResponse implements JsonSerializable
{
    public function __construct(
        public bool $ok,
        public ?string $output = null,
        public ?string $action = null,
        public ?string $error = null,
    ) {}

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        return (array) $this;
    }
}