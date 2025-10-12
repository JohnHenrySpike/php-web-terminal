<?php
declare(strict_types=1);

namespace SpikeTerminal\Presentation;

readonly class TerminalUiController
{
    public function __invoke(): void
    {
        require(__DIR__ . '/assets/main.php');
    }
}