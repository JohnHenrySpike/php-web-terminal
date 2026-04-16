<?php

declare(strict_types=1);

namespace SpikeTerminal;

use Exception;

interface AuthProviderInterface
{
    public function setToken(?string $token): void;

    /**
     * @throws Exception
     */
    public function auth(): void;
}
