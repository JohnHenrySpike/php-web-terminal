<?php
declare(strict_types=1);

namespace SpikeTerminal\Commands;

use SpikeTerminal\Domain\CommandInterface;

class DateCommand implements CommandInterface
{
    private const string NAME = 'date';
    private const string DESCRIPTION = 'Show server date';

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
        return date('Y-m-d H:i:s');
    }
}
