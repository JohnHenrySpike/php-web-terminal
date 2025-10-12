<?php
declare(strict_types=1);

namespace SpikeTerminal\Commands;

use SpikeTerminal\Domain\CommandInterface;

class ServerCommand implements CommandInterface
{
    private const string NAME = 'server';
    private const string DESCRIPTION = 'Show server info';

    public static function name(): string
    {
        return self::NAME;
    }

    public function getDescription(): string
    {
        return self::DESCRIPTION;
    }

    public function execute(array $args): string
    {
        $info = [
            'time' => date('Y-m-d H:i:s'),
            'php' => PHP_VERSION,
            'sapi' => PHP_SAPI,
            'hostname' => gethostname() ?: 'unknown',
            'user' => get_current_user() ?: 'unknown',
        ];
        $lines = [];
        foreach ($info as $k => $v) { $lines[] = "$k: $v"; }
        return implode("\n", $lines);
    }
}
