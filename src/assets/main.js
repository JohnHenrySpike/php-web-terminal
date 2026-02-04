const screen = document.getElementById('screen');
const input = document.getElementById('input');
const logoutBtn = document.getElementById('logout');
const authDiv = document.getElementById('auth');
const tokenInput = document.getElementById('token');
const saveToken = document.getElementById('setToken');
const history = [];
let histIdx = -1;

function getToken() {
    return localStorage.getItem('TERMINAL_TOKEN') || '';
}

function setToken(t) {
    if (t) localStorage.setItem('TERMINAL_TOKEN', t);
}

function clearToken() {
    localStorage.removeItem('TERMINAL_TOKEN');
}

function print(text, cls) {
    const div = document.createElement('div');
    div.className = 'line' + (cls ? ' ' + cls : '');
    div.textContent = text;
    screen.appendChild(div);
    screen.scrollTop = screen.scrollHeight;
}

function clearScreen() {
    screen.innerHTML = '';
}

let tokenWaiter = null;

function showAuth() {
    authDiv.style.display = 'flex';
    tokenInput.value = '';
    tokenInput.focus();
}

function hideAuth() {
    authDiv.style.display = 'none';
}

async function ensureToken() {
    const existing = getToken();
    if (existing) return existing;
    showAuth();
    if (!tokenWaiter) {
        tokenWaiter = new Promise((resolve) => {
            saveToken.onclick = async () => {
                const tok = tokenInput.value;
                hideAuth();
                setToken(tok);
                print('Token set.');
                resolve(tok);
                tokenWaiter = null;
            };
            tokenInput.onkeydown = (e) => {
                if (e.key === 'Enter') {
                    saveToken.click();
                }
            };
        });
    }
    return tokenWaiter;
}

async function run(cmd) {
    if (!cmd.trim()) return;
    // client-side history handling
    history.push(cmd);
    histIdx = history.length;
    print('> ' + cmd);
    if (cmd === 'history') {
        const lines = history.map((h, i) => (String(i + 1).padStart(3, ' ') + '  ' + h));
        print(lines.join('\n'));
        return;
    }
    try {
        const token = await ensureToken();
        const res = await fetch(location.href, {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'apiKey': token},
            body: JSON.stringify({command: cmd})
        });
        if (res.status === 401) {
            clearToken();
            showAuth();
            print('Unauthorized. Please login.');
            return;
        }
        const data = await res.json();
        if (!data.ok) {
            print('Error: ' + (data.error || res.status));
            if (data.output && data.output.length) {
                print('Error message: ' + (data.output || res.status));
            }
            return;
        }
        if (data.action === 'clear') {
            clearScreen();
            return;
        }
        if (typeof data.output === 'string' && data.output.length) {
            print(data.output);
        }
    } catch (e) {
        print('Network error');
    }
}

input.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
        const v = input.value;
        input.value = '';
        run(v);
    } else if (e.key === 'ArrowUp') {
        if (histIdx > 0) {
            histIdx--;
            input.value = history[histIdx] || '';
            setTimeout(() => input.setSelectionRange(9999, 9999));
        }
    } else if (e.key === 'ArrowDown') {
        if (histIdx < history.length) {
            histIdx++;
            input.value = history[histIdx] || '';
            setTimeout(() => input.setSelectionRange(9999, 9999));
        }
    } else if (e.ctrlKey && e.key.toLowerCase() === 'l') {
        e.preventDefault();
        clearScreen();
    }
});
screen.addEventListener('click', () => input.focus());
logoutBtn.addEventListener('click', () => {
    clearToken();
    hideAuth();
    clearScreen();
    print('Logged out. New token will be requested on next command.');
    input.focus();
});
input.focus();
print('Web Terminal');
print("Type 'help' to see available commands.\n");