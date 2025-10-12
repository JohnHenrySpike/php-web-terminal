<?php
declare(strict_types=1);

namespace SpikeTerminal\Commands;

use SpikeTerminal\Application\CommandRegistry;
use SpikeTerminal\Domain\CommandInterface;

class HelpCommand implements CommandInterface
{
    private const string NAME = 'help';
    private const string DESCRIPTION = 'Show this help';

    public function __construct(
        private readonly CommandRegistry $registry
    ) {}

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
        $list = [];
        foreach ($this->registry->list() as $command){
            $list[] = $command->name() . "\t" .' - ' . $command->getDescription();
        }
        return implode("\n", $list);
    }
}
