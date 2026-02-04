<?php

declare(strict_types=1);

namespace SpikeTerminal\Application;

use Throwable;

final class TerminalService
{
    public function __construct(
        public ?CommandRegistryInterface $registry = null,
        public ?AuthProviderInterface $authProvider = null,
    ) {
        $this->registry ??= new CommandRegistry();
    }

    public function registerAuthProvider(AuthProviderInterface $authProvider): void
    {
        $this->authProvider = $authProvider;
    }

    public function getRegistry(): CommandRegistryInterface
    {
        return $this->registry;
    }

    public function renderInterface(): void
    {
        require(__DIR__ . '/../assets/main.php');
    }

    public function run(string $line): TerminalResponse
    {
        if ($this->authProvider) {
            try {
                $this->authProvider->auth();
            } catch (Throwable $e) {
                return new TerminalResponse(ok: false, code: 401, output: $e->getMessage());
            }

        }
        $line = trim($line);
        if ($line === '') {
            return new TerminalResponse(ok: true);
        }
        [$command, $args] = $this->parse($line);
        if ($command === 'clear') {
            return new TerminalResponse(ok: true, action: 'clear');
        }
        if (!$this->registry->has($command)) {
            return new TerminalResponse(
                ok: true,
                output: "Unknown command: $command. Type 'help' for list.",
            );
        }
        $handler = $this->registry->get($command);
        try {
            $out = $handler?->execute($args) ?? '';
            return new TerminalResponse(ok: true, output: $out);
        } catch (Throwable $e) {
            return new TerminalResponse(ok: false, output: $e->getMessage(), error: 'Server error');
        }
    }

    /**
     * @return array{0:string,1:array<int,string>}
     */
    private function parse(string $line): array
    {
        $tokens = [];
        $token = '';
        $inQuote = false;
        $quoteChar = '';
        $len = strlen($line);
        for ($i = 0; $i < $len; $i++) {
            $ch = $line[$i];
            if ($inQuote) {
                if ($ch === $quoteChar) {
                    $inQuote = false;
                    continue;
                }
                if ($ch === '\\' && $i + 1 < $len) {
                    $i++;
                    $token .= $line[$i];
                    continue;
                }
            } else {
                if ($ch === '"' || $ch === "'") {
                    $inQuote = true;
                    $quoteChar = $ch;
                    continue;
                }
                if (ctype_space($ch)) {
                    if ($token !== '') {
                        $tokens[] = $token;
                        $token = '';
                    } continue;
                }
            }
            $token .= $ch;
        }
        if ($token !== '') {
            $tokens[] = $token;
        }
        $cmd = strtolower($tokens[0] ?? '');
        $args = array_slice($tokens, 1);
        return [$cmd, $args];
    }
}
