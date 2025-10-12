# PHP Web Terminal (Single-Endpoint)

A minimal web-based terminal emulator implemented in PHP. All interactions happen via one resource:
- GET /index.php → serves the UI
- POST /index.php (JSON) → executes whitelisted commands on the server via PHP handlers (no eval, no shell execution)

## Features
- Single endpoint (UI + API)
- Commands implemented as PHP functions only (no `eval`, no system command execution)
- Bearer token auth (token requested on page load and stored in localStorage)
- Minimal inline HTML/CSS/JS
- Client-side command history

## Installation (Composer library)
This project can be installed as a Composer library.

1. Require it in your project:
   ```sh
   composer require spike/php-web-terminal
   ```
2. Publish or serve the included web entry point `index.php`, or embed the services in your own controller.

Autoloading follows PSR-4: the `App\\` namespace maps to `src/`.

### Embedding as a library (example)

```php
<?php
use SpikeTerminal\Application\{TerminalService};
use SpikeTerminal\Application\CommandRegistry;
require __DIR__ . '/vendor/autoload.php';
$registry = new CommandRegistry();
$registry->register(new SomeCommand($registry));

```

## Getting Started (Standalone)

### Option A: Local PHP
1. Ensure PHP 8.1+ is installed.
2. From the project root, start the PHP dev server:
   ```sh
   php -S localhost:8000
   ```
3. Open http://localhost:8000/ in your browser.

### Option B: Docker Compose
1. Ensure Docker and Docker Compose are installed.
2. From the project root, start the service:
   ```sh
   docker compose up
   ```
3. Open http://localhost:8000/ in your browser.
4. Stop with Ctrl+C, or run in the background with `-d` and stop with `docker compose down`.

The sandbox directory `storage/` is created automatically (a sample `readme.txt` is included).

## Security Notes
- All commands are server-side PHP handlers; there is no `eval()` and no OS shell execution.
- In production, use signed JWTs and HTTPS.

## Architecture (OOP, clean-like)
The single endpoint remains `/index.php`, but the logic is refactored into layers under `src/`:

- Domain: `App\Domain` — core contracts (e.g., `CommandInterface`).
- Application: `App\Application` — orchestration and use-cases (e.g., `TerminalService`, `CommandRegistry`).
- Presentation: `App\Presentation` — HTTP controller (`TerminalController`) that render the UI (GET).
- Commands: `App\Commands` — each command is a class implementing `CommandInterface`.

## Customizing Commands
To add or modify commands:
1. Create a class in `src/Commands` implementing `App\\Domain\\CommandInterface` with  `name()` and `execute(array $args): string`.
2. Register it in `index.php` via `$registry->register(new YourCommand(...deps...));` before the controller handles the request.

## Notes
- No use of `eval()` or OS shell execution. All commands are pure PHP.
