<?php
declare(strict_types=1);

namespace SpikeTerminal\Domain;

interface CommandInterface
{
    /**
     * Command name used for dispatch.
     */
    public static function name(): string;

    /**
     * Retrieves the description of the current instance.
     *
     * @return string The description text.
     */
    public function getDescription(): string;

    /**
     * Execute the command.
     * @param array<int,string> $args
     * @return string Output to be printed
     */
    public function execute(array $args): string;
}
