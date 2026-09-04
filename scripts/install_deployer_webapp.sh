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
#   DEPLOYER_VARWWW         WSGI search dir (default /var/www; testing)
#   DEPLOYER_PA_API         PythonAnywhere API base (testing)
#   DEPLOYER_PA_USER        override the account name reported by whoami
#
# It is safe to re-run: existing backups are kept and the WSGI patch is
# applied only once (marker-detected). Always back up the WSGI file first
# (the script creates .bak.<timestamp> automatically).
# ---------------------------------------------------------------------------
set -euo pipefail

PA_USER="${DEPLOYER_PA_USER:-$(whoami)}"
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
                     "$HOME_DIR/scraper4/scraper4.py" "$HOME_DIR/mysite/scraper4.py" \
                     "$REPO_SRC/scraper4.py"; do
        if [ -f "$candidate" ]; then TARGET="$candidate"; break; fi
    done
fi
if [ -z "$TARGET" ]; then
    TARGET="$(find "$HOME_DIR" -maxdepth 4 -name 'scraper4.py' \
        -not -path '*/.deployer/*' -not -path '*/venv/*' -not -path '*/.cache/*' 2>/dev/null | head -1)"
fi

BRANCHES="${3:-${DEPLOYER_BRANCHES:-}}"
REPO="${DEPLOYER_REPO:-fazilatma/amphp}"
GH_TOKEN="${DEPLOYER_GITHUB_TOKEN:-${GITHUB_TOKEN:-}}"
WEB_TOKEN="${DEPLOYER_WEB_TOKEN:-}"
USER_LOWER="$(printf '%s' "$PA_USER" | tr '[:upper:]' '[:lower:]')"
DOMAIN_LOWER="$USER_LOWER.pythonanywhere.com"
TOKEN_FILE="$HOME_DIR/.pythonanywhere_api_token"
VARWWW="${DEPLOYER_VARWWW:-/var/www}"
API="${DEPLOYER_PA_API:-https://www.pythonanywhere.com/api/v0/user/$PA_USER}"
SYSTEM_PY="$(command -v python3 || true)"

echo "==> کاربر:        $PA_USER"
echo "==> پوشه کد:      $REPO_SRC"
echo "==> فایل هدف:     ${TARGET:-پیدا نشد!}"
if [ -n "$BRANCHES" ]; then
    echo "==> برنچ‌ها:      $BRANCHES (فقط این‌ها)"
else
    echo "==> برنچ‌ها:      همه برنچ‌های ریپو (جستجوی خودکار)"
fi

# --- checks -------------------------------------------------------------------
[ -f "$REPO_SRC/deployer.py" ] || { echo "خطا: deployer.py در $REPO_SRC پیدا نشد" >&2; exit 1; }
[ -n "$TARGET" ] || { echo "خطا: مسیر scraper4.py را به عنوان آرگومان دوم بدهید" >&2; exit 1; }
[ -f "$TARGET" ] || { echo "خطا: فایل هدف وجود ندارد: $TARGET" >&2; exit 1; }

# ---------------------------------------------------------------------------
# Locate (or create) the real WSGI file.
# The filename depends on the web app domain: default apps live at
# /var/www/<username>_pythonanywhere_com_wsgi.py; custom domains produce
# /var/www/<username>_<domain_with_underscores>_wsgi.py. We discover it
# instead of guessing, and only create a web app when none exists.
# ---------------------------------------------------------------------------
find_wsgi() {
    local f
    for f in "$VARWWW/${USER_LOWER}_pythonanywhere_com_wsgi.py" \
             "$VARWWW/${PA_USER}_pythonanywhere_com_wsgi.py"; do
        [ -f "$f" ] && { printf '%s' "$f"; return; }
    done
    find "$VARWWW" -maxdepth 1 -type f -name '*_wsgi.py' -writable 2>/dev/null | sort | head -n 1
}
WSGI_FILE="${DEPLOYER_WSGI_FILE:-}"
if [ -z "$WSGI_FILE" ]; then
    WSGI_FILE="$(find_wsgi || true)"
fi

if [ -z "$WSGI_FILE" ] && [ -s "$TOKEN_FILE" ] && [ -n "$SYSTEM_PY" ]; then
    echo "==> وب‌اپی پیدا نشد؛ ایجاد web app از طریق API پایتون‌انی‌ورلد…"
    API_AUTH="$HOME_DIR/.deployer/.pa-auth-$$"
    API_RESP="$HOME_DIR/.deployer/.pa-response-$$"
    TOKEN="$(tr -d '[:space:]' < "$TOKEN_FILE")"
    printf 'header = "Authorization: Token %s"\n' "$TOKEN" > "$API_AUTH"
    chmod 600 "$API_AUTH"
    unset TOKEN
    api_call() {
        curl --config "$API_AUTH" -sS --show-error -o "$API_RESP" \
            --write-out '%{http_code}' --request "$1" --connect-timeout 20 \
            --max-time 120 "${@:2}"
    }
    STATUS="$(api_call GET "$API/webapps/")"
    if [ "$STATUS" != 200 ]; then
        cat "$API_RESP" || true
        rm -f "$API_AUTH" "$API_RESP"
        echo "خطا: احراز هویت API پایتون‌انی‌ورلد ناموفق بود (HTTP $STATUS)" >&2
        exit 1
    fi
    DOMAIN_EXISTS=0
    if "$SYSTEM_PY" - "$API_RESP" "$DOMAIN_LOWER" <<'PY'
import json, sys
rows = json.load(open(sys.argv[1], encoding="utf-8"))
wanted = sys.argv[2].lower()
sys.exit(0 if any(str(r.get("domain_name", "")).lower() == wanted for r in rows if isinstance(r, dict)) else 1)
PY
    then DOMAIN_EXISTS=1; fi

    if [ "$DOMAIN_EXISTS" != 1 ]; then
        MAJOR="$("$SYSTEM_PY" -c 'import sys;print(sys.version_info.major)')"
        MINOR="$("$SYSTEM_PY" -c 'import sys;print(sys.version_info.minor)')"
        STATUS="$(api_call POST "$API/webapps/" \
            --data-urlencode "domain_name=$DOMAIN_LOWER" \
            --data-urlencode "python_version=python${MAJOR}${MINOR}")"
        if [ "$STATUS" != 200 ] && [ "$STATUS" != 201 ]; then
            cat "$API_RESP" || true
            rm -f "$API_AUTH" "$API_RESP"
            echo "خطا: ساخت وب‌اپ ناموفق بود (HTTP $STATUS)" >&2
            exit 1
        fi
        echo "==> وب‌اپ به نام $DOMAIN_LOWER ساخته شد؛ منتظر ساخته‌شدن فایل WSGI…"
        # Point a new app at the Scraper4 venv when this layout exists.
        if [ -d "$HOME_DIR/scraper4/venv" ]; then
            api_call PATCH "$API/webapps/$DOMAIN_LOWER/" \
                --data-urlencode "virtualenv_path=$HOME_DIR/scraper4/venv" >/dev/null || true
        fi
    else
        echo "==> وب‌اپ موجود است ولی فایل WSGI هنوز در /var/www نیست؛ منتظر می‌مانیم…"
    fi
    rm -f "$API_AUTH" "$API_RESP"

    for n in $(seq 1 30); do
        WSGI_FILE="$(find_wsgi || true)"
        [ -n "$WSGI_FILE" ] && break
        echo "    منتظر WSGI ($n/30)…"
        sleep 2
    done
fi

if [ -z "$WSGI_FILE" ]; then
    cat >&2 <<'EOF'
خطا: هیچ فایل WSGI در /var/www پیدا نشد و توکن API پایتون‌انی‌ورلد هم موجود نیست.

برای ساخت وب‌اپ (یک‌بار) در پایتون‌انی‌ورلد:
  تب Web → Create a new web app → Manual configuration → Python 3.10 → Next
سپس همین اسکریپت را دوباره اجرا کنید؛ خودش فایل WSGI را پیدا و پچ می‌کند.

اگر token دارید ولی می‌خواهید دستی بدهید:
  export DEPLOYER_WSGI_FILE=/var/www/Fazilatma_pythonanywhere_com_wsgi.py
EOF
    exit 1
fi
[ -w "$WSGI_FILE" ] || { echo "خطا: فایل WSGI قابل نوشتن نیست: $WSGI_FILE" >&2; exit 1; }

echo "==> WSGI:         $WSGI_FILE"
ALL_WSGI="$(find "$VARWWW" -maxdepth 1 -type f -name '*_wsgi.py' -writable 2>/dev/null | sort || true)"
if [ -n "$ALL_WSGI" ]; then
    echo "---- فایل‌های WSGI موجود در /var/www ----"
    echo "$ALL_WSGI" | sed 's/^/     /'
fi

# --- 1. copy deployer ---------------------------------------------------------
mkdir -p "$HOME_DIR/.deployer"
SRC_DEPLOYER="$(readlink -f "$REPO_SRC/deployer.py" 2>/dev/null || printf '%s' "$REPO_SRC/deployer.py")"
DST_DEPLOYER="$HOME_DIR/.deployer/deployer.py"
if [ "$SRC_DEPLOYER" != "$(readlink -f "$DST_DEPLOYER" 2>/dev/null || printf '%s' "$DST_DEPLOYER")" ]; then
    cp -f "$REPO_SRC/deployer.py" "$DST_DEPLOYER"
    echo "==> deployer.py کپی شد → ~/.deployer/deployer.py"
else
    echo "==> deployer.py در ~/.deployer/deployer.py موجود است (بدون کپی)"
fi

# --- 2. write WSGI wrapper (env values escaped by Python) ---------------------
python3 - "$HOME_DIR/.deployer/deployer_wsgi.py" "$REPO" "$BRANCHES" "$TARGET" "$GH_TOKEN" "$WEB_TOKEN" <<'PY'
import json, os, sys
path, repo, branches, target, gh_token, web_token = sys.argv[1:7]
env = {
    "DEPLOYER_REPO": repo,
    "DEPLOYER_TARGET": target,
    "DEPLOYER_GITHUB_TOKEN": gh_token,
    "DEPLOYER_WEB_TOKEN": web_token,
}
if branches:
    env["DEPLOYER_BRANCHES"] = branches
# Empty DEPLOYER_BRANCHES means: scan every branch of the repo automatically.
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
PLACEHOLDER=0
if grep -qF "$MARKER" "$WSGI_FILE"; then
    echo "==> پچ /deployer از قبل اعمال شده است (تغییری ندادیم)"
elif grep -qE '^[[:space:]]*(application|app)[[:space:]]*=' "$WSGI_FILE"; then
    ORIG_NAME="$(grep -E '^[[:space:]]*(application|app)[[:space:]]*=' "$WSGI_FILE" | head -n 1 | sed -E 's/^[[:space:]]*//; s/[[:space:]]*=.*//')"
    cp -f "$WSGI_FILE" "$WSGI_FILE.bak.$(date +%Y%m%d%H%M%S)"
    cat >> "$WSGI_FILE" <<EOF

# --- scraper4 deployer mount (managed by install_deployer_webapp.sh) ---
import os as _os
import sys as _sys
_d = _os.path.expanduser('~/.deployer')
if _d not in _sys.path:
    _sys.path.insert(0, _d)
from deployer_wsgi import application as _deployer_app
_original_application = $ORIG_NAME
def application(environ, start_response):
    if environ.get('PATH_INFO', '').startswith('/deployer'):
        return _deployer_app(environ, start_response)
    return _original_application(environ, start_response)
EOF
    echo "==> فایل WSGI پچ شد (پشتیبان: $(basename "$(ls -t "$WSGI_FILE".bak.* | head -1)"))"
else
    # No application/app variable at all (plain/blank template). Install a safe
    # guarded WSGI so the /deployer/ page works; the main site can be configured
    # afterwards without losing the deployer mount. Backup is always kept.
    BACKUP="$WSGI_FILE.bak.$(date +%Y%m%d%H%M%S)"
    cp -f "$WSGI_FILE" "$BACKUP"
    cat > "$WSGI_FILE.tmp.$$" <<'EOF'
# --- scraper4 deployer mount (managed by install_deployer_webapp.sh) ---
import os as _os
import sys as _sys
_d = _os.path.expanduser('~/.deployer')
if _d not in _sys.path:
    _sys.path.insert(0, _d)
from deployer_wsgi import application as _deployer_app

def application(environ, start_response):
    path = environ.get('PATH_INFO', '')
    if path.startswith('/deployer'):
        return _deployer_app(environ, start_response)
    body = ('Scraper4 main site is not configured yet. Install it first '
            '(run install_pythonanywhere.sh), then re-run '
            'install_deployer_webapp.sh so both are mounted.').encode('utf-8')
    start_response('200 OK', [('Content-Type', 'text/plain; charset=utf-8'),
                              ('Content-Length', str(len(body)))])
    return [body]
EOF
    mv -f "$WSGI_FILE.tmp.$$" "$WSGI_FILE"
    PLACEHOLDER=1
    echo "==> فایل WSGI متغیر application/app نداشت؛ mount امن جایگزین شد (پشتیبان: $(basename "$BACKUP"))"
fi

# --- 4. reload ------------------------------------------------------------------
if [ -s "$TOKEN_FILE" ]; then
    API_TOKEN="$(tr -d '[:space:]' < "$TOKEN_FILE")"
    echo "==> درخواست reload از API…"
    curl -sS -X POST -H "Authorization: Token $API_TOKEN" \
        "$API/webapps/$DOMAIN_LOWER/reload/" || true
    echo
else
    echo "==> توکن API پیدا نشد — لطفاً در تب Web دکمه Reload را بزنید"
fi

# --- 5. derived page URL (from the actual WSGI file name) ------------------------
WSGI_NAME="$(basename "$WSGI_FILE")"
WSGI_CORE="${WSGI_NAME%_wsgi.py}"
if [[ "$WSGI_CORE" == *_pythonanywhere_com ]]; then
    PAGE_DOMAIN="$DOMAIN_LOWER"
else
    # /var/www/<username>_<domain_with_underscores>_wsgi.py -> domain
    PAGE_DOMAIN="${WSGI_CORE#${PA_USER}_}"
    [ "$PAGE_DOMAIN" = "$WSGI_CORE" ] && PAGE_DOMAIN="${WSGI_CORE#${USER_LOWER}_}"
    [ "$PAGE_DOMAIN" = "$WSGI_CORE" ] && PAGE_DOMAIN="${WSGI_CORE#*_}"
    PAGE_DOMAIN="${PAGE_DOMAIN//_/.}"
fi
DEPLOYER_URL="https://$PAGE_DOMAIN/deployer/"

cat <<EOF

============================================================
✅ نصب شد. صفحه دیپلوی‌ر از این آدرس در مرورگر باز می‌شود:

   $DEPLOYER_URL

   (منتظر reload بمانید؛ چند ثانیه طول می‌کشد)

بررسی سریع با curl:
   curl -s $DEPLOYER_URL | head -5

اگر DEPLOYER_WEB_TOKEN تنظیم کرده‌اید، دکمه‌های
«نصب» و «بازگشت» با همان رمز فعال می‌شوند؛ بدون آن،
صفحه فقط وضعیت را نمایش می‌دهد (حالت امن).
EOF
if [ "$PLACEHOLDER" = 1 ]; then
cat <<'EOF'

⚠️  توجه: فایل WSGI قبلی هیچ متغیر application نداشت (وب‌اپ خالی بود).
    اسکریپت یک mount امن جایگزین کرد تا صفحه دیپلوی‌ر کار کند.
    برای راه‌اندازی سایت اصلی (scraper4) هم بعداً این را اجرا کنید:

        cd ~/amphp 2>/dev/null || cd ~
        bash install_pythonanywhere.sh        # سپس دوباره:
        bash install_deployer_webapp.sh       # تا هر دو mount شوند

    (نسخه قبلی WSGI در پشتیبان .bak.* نگهداری شد.)
EOF
fi
cat <<'EOF'

نکته: اگر سایت اصلی هنوز اجرا نشده، اسکریپت اصلی پروژه
(install_pythonanywhere.sh در ریپو) را هم اجرا کنید تا
سایت اصلی روی همان وب‌اپ راه‌اندازی شود.
============================================================
EOF
