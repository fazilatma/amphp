#!/usr/bin/env bash
# ---------------------------------------------------------------------------
# install_deployer_webapp.sh — mount the Scraper4 standalone deployer on a
# PythonAnywhere web app and expose its page at /deployer/.
#
# Run this from the PythonAnywhere Bash console (project must be cloned,
# e.g. ~/amphp). The script:
#   1. copies deployer.py into ~/.deployer/
#   2. writes ~/.deployer/deployer_wsgi.py with your configuration
#   3. patches the main WSGI file (/var/www/<user>_pythonanywhere_com_wsgi.py)
#      so that /deployer/* is answered by the deployer and everything else
#      keeps answering with the main site (scraper4.py)
#   4. reloads the web app (via the PythonAnywhere API when a token exists)
#   5. prints the browser URL: https://<username>.pythonanywhere.com/deployer/
#
# Usage:
#   bash scripts/install_deployer_webapp.sh [SOURCE_DIR] [SCRAPER4_TARGET] [BRANCHES]
#     SOURCE_DIR       repo folder containing deployer.py       (default: script's repo)
#     SCRAPER4_TARGET  full path of the installed scraper4.py   (auto-detected)
#     BRANCHES         comma separated branch names             (default: arena/01a0640f-amphp)
#
# Optional env vars:
#   DEPLOYER_SOURCE_BRANCH  branch to fetch deployer.py from GitHub (default arena/01a06abd-amphp)
#   DEPLOYER_BRANCHES       same as BRANCHES argument
#   DEPLOYER_REPO           GitHub repository owner/name (default fazilatma/amphp)
#   DEPLOYER_GITHUB_TOKEN   token for private repositories
#   DEPLOYER_WEB_TOKEN      password for the /deployer/ page (install/rollback)
#   DEPLOYER_WSGI_FILE      override the WSGI file path (testing)
#
# It is safe to re-run: existing backups are kept and the WSGI patch is
# applied only once (marker-detected). Always back up the WSGI file first
# (the script creates .bak.<timestamp> automatically).
# ---------------------------------------------------------------------------
set -euo pipefail

PA_USER="$(whoami)"
HOME_DIR="$HOME"
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
# Repo root: explicit argument, else the folder containing this script.
REPO_SRC="${1:-$(dirname "$SCRIPT_DIR")}"
SOURCE_BRANCH="${DEPLOYER_SOURCE_BRANCH:-arena/01a06abd-amphp}"
RAW_BASE="https://raw.githubusercontent.com/${DEPLOYER_REPO:-fazilatma/amphp}/$SOURCE_BRANCH"

# --- locate deployer.py -------------------------------------------------------
# If we were not run from the repo (e.g. copied to ~), try the common places.
if [ ! -f "$REPO_SRC/deployer.py" ]; then
    for cand in "$HOME_DIR/amphp/deployer.py" "$HOME_DIR/deployer.py"; do
        if [ -f "$cand" ]; then REPO_SRC="$(dirname "$cand")"; break; fi
    done
fi
if [ ! -f "$REPO_SRC/deployer.py" ]; then
    echo "==> deployer.py محلی پیدا نشد؛ تلاش برای دانلود از GitHub…"
    mkdir -p "$HOME_DIR/.deployer"
    curl -fsSL "$RAW_BASE/deployer.py" -o "$HOME_DIR/.deployer/deployer.py" \
        || { echo "خطا: deployer.py پیدا یا دانلود نشد. ابتدا وارد پوشه ریپو شوید (cd ~/amphp) و شاخه درست را بگیرید" >&2; exit 1; }
    REPO_SRC="$HOME_DIR/.deployer"
fi

# --- target scraper4.py (auto-detect when not given) -------------------------
TARGET="${2:-}"
if [ -z "$TARGET" ]; then
    for candidate in "$HOME_DIR/amphp/scraper4.py" "$HOME_DIR/scraper4.py" \
                     "$HOME_DIR/mysite/scraper4.py" "$REPO_SRC/scraper4.py"; do
        if [ -f "$candidate" ]; then TARGET="$candidate"; break; fi
    done
fi
if [ -z "$TARGET" ]; then
    TARGET="$(find "$HOME_DIR" -maxdepth 4 -name 'scraper4.py' \
        -not -path '*/.deployer/*' -not -path '*/venv/*' -not -path '*/.cache/*' 2>/dev/null | head -1)"
fi

BRANCHES="${3:-${DEPLOYER_BRANCHES:-arena/01a0640f-amphp}}"
REPO="${DEPLOYER_REPO:-fazilatma/amphp}"
GH_TOKEN="${DEPLOYER_GITHUB_TOKEN:-${GITHUB_TOKEN:-}}"
WEB_TOKEN="${DEPLOYER_WEB_TOKEN:-}"
WSGI_FILE="${DEPLOYER_WSGI_FILE:-/var/www/${PA_USER}_pythonanywhere_com_wsgi.py}"

echo "==> کاربر:        $PA_USER"
echo "==> پوشه کد:      $REPO_SRC"
echo "==> فایل هدف:     ${TARGET:-پیدا نشد!}"
echo "==> برنچ‌ها:      $BRANCHES"
echo "==> WSGI:         $WSGI_FILE"

# --- checks -------------------------------------------------------------------
[ -f "$REPO_SRC/deployer.py" ] || { echo "خطا: deployer.py در $REPO_SRC پیدا نشد" >&2; exit 1; }
[ -n "$TARGET" ] || { echo "خطا: مسیر scraper4.py را به عنوان آرگومان دوم بدهید" >&2; exit 1; }
[ -f "$TARGET" ] || { echo "خطا: فایل هدف وجود ندارد: $TARGET" >&2; exit 1; }
[ -f "$WSGI_FILE" ] || { echo "خطا: فایل WSGI پیدا نشد: $WSGI_FILE — اگر وب‌اپ ندارید ابتدا از تب Web یک وب‌اپ بسازید" >&2; exit 1; }

# --- 1. copy deployer ---------------------------------------------------------
mkdir -p "$HOME_DIR/.deployer"
cp -f "$REPO_SRC/deployer.py" "$HOME_DIR/.deployer/deployer.py"
echo "==> deployer.py کپی شد → ~/.deployer/deployer.py"

# --- 2. write WSGI wrapper (env values escaped by Python) ---------------------
python3 - "$HOME_DIR/.deployer/deployer_wsgi.py" "$REPO" "$BRANCHES" "$TARGET" "$GH_TOKEN" "$WEB_TOKEN" <<'PY'
import json, os, sys
path, repo, branches, target, gh_token, web_token = sys.argv[1:7]
env = {
    "DEPLOYER_REPO": repo,
    "DEPLOYER_BRANCHES": branches,
    "DEPLOYER_TARGET": target,
    "DEPLOYER_GITHUB_TOKEN": gh_token,
    "DEPLOYER_WEB_TOKEN": web_token,
}
lines = [
    "# Auto-generated by install_deployer_webapp.sh — do not edit by hand.",
    "# The deployer page lives at https://<username>.pythonanywhere.com/deployer/",
    "import os, sys",
    "_d = os.path.expanduser('~/.deployer')",
    "if _d not in sys.path:",
    "    sys.path.insert(0, _d)",
    "os.environ.update(" + json.dumps(env) + ")",
    "import deployer",
    "application = deployer.wsgi_application",
]
with open(path, "w", encoding="utf-8") as fh:
    fh.write("\n".join(lines) + "\n")
PY
echo "==> ~/.deployer/deployer_wsgi.py نوشته شد"

# --- 3. patch main WSGI (idempotent, with backup) ------------------------------
MARKER="# --- scraper4 deployer mount (managed by install_deployer_webapp.sh) ---"
if grep -qF "$MARKER" "$WSGI_FILE"; then
    echo "==> پچ /deployer از قبل اعمال شده است (تغییری ندادیم)"
elif grep -qE "^application[[:space:]]*=" "$WSGI_FILE"; then
    cp -f "$WSGI_FILE" "$WSGI_FILE.bak.$(date +%Y%m%d%H%M%S)"
    cat >> "$WSGI_FILE" <<'EOF'

# --- scraper4 deployer mount (managed by install_deployer_webapp.sh) ---
import os as _os
import sys as _sys
_d = _os.path.expanduser('~/.deployer')
if _d not in _sys.path:
    _sys.path.insert(0, _d)
from deployer_wsgi import application as _deployer_app
_original_application = application
def application(environ, start_response):
    if environ.get('PATH_INFO', '').startswith('/deployer'):
        return _deployer_app(environ, start_response)
    return _original_application(environ, start_response)
EOF
    echo "==> فایل WSGI پچ شد (پشتیبان: $(basename "$(ls -t "$WSGI_FILE".bak.* | head -1)"))"
else
    echo "خطا: در فایل WSGI متغیر application پیدا نشد؛ آن را دستی بررسی کنید" >&2
    exit 1
fi

# --- 4. reload ------------------------------------------------------------------
DOMAIN="$(echo "$PA_USER" | tr 'A-Z' 'a-z').pythonanywhere.com"
TOKEN_FILE="$HOME_DIR/.pythonanywhere_api_token"
if [ -f "$TOKEN_FILE" ]; then
    API_TOKEN="$(tr -d '[:space:]' < "$TOKEN_FILE")"
    echo "==> درخواست reload از API…"
    curl -sS -X POST -H "Authorization: Token $API_TOKEN" \
        "https://www.pythonanywhere.com/api/v0/user/$PA_USER/webapps/$DOMAIN/reload/" || true
    echo
else
    echo "==> توکن API پیدا نشد — لطفاً در تب Web دکمه Reload را بزنید"
fi

# --- 5. result -------------------------------------------------------------------
cat <<EOF

============================================================
✅ نصب شد. صفحه دیپلوی‌ر از این آدرس باز می‌شود:

   https://$DOMAIN/deployer/

   (منتظر reload بمانید؛ چند ثانیه طول می‌کشد)

بررسی سریع با curl:
   curl -s https://$DOMAIN/deployer/ | head -5

اگر DEPLOYER_WEB_TOKEN تنظیم کرده‌اید، دکمه‌های
«نصب» و «بازگشت» با همان رمز فعال می‌شوند؛ بدون آن،
صفحه فقط وضعیت را نمایش می‌دهد (حالت امن).
============================================================
EOF
