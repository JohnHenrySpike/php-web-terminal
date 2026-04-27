<?php

declare(strict_types=1);

namespace SpikeTerminal;

use JsonSerializable;

class TerminalResponse implements JsonSerializable
{
    public function __construct(
        public bool $ok,
        public ?string $output = null,
        public ?string $action = null,
        public ?string $error = null,
    ) {}

    public function jsonResponse(): string
    {
        return json_encode($this);
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'ok' => $this->ok,
            'output' => $this->output,
            'action' => $this->action,
            'error' => $this->error,
        ];
    }
}
