<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP Web Terminal</title>
    <style><?php echo file_get_contents(__DIR__.'/main.css')?></style>
</head>
<body>
<div id="app">
    <div id="header">
        <div>Web Terminal</div>
        <div>
            <button id="logout" class="btn" title="Clear token and reset">Logout</button>
        </div>
    </div>
    <div id="auth" style="display:none;padding:8px 12px;border-bottom:1px solid #222;gap:.5rem;align-items:center">
        <label class="meta" for="token">Token:</label>
        <input id="token" type="password" class="input" style="max-width:240px;border:1px solid #30363d;padding:4px 8px;border-radius:6px" placeholder="Enter token" />
        <button id="setToken" class="btn">SaveToken</button>
    </div>
    <div id="screen" tabindex="0" aria-label="Terminal output" role="log"></div>
    <div class="prompt">
        <span class="meta" id="cwd">/</span>
        <label for="input"></label>
        <input id="input" class="input" autocomplete="off" placeholder="Type a command (help)" />
    </div>
    <div class="kbd" style="padding:6px 12px">Ctrl+L to clear</div>
</div>
<script><?php echo file_get_contents(__DIR__.'/main.js')?></script>
</body>
</html>