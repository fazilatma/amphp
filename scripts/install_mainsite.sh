#!/usr/bin/env bash
# ---------------------------------------------------------------------------
# install_mainsite.sh — quick one-shot install of the Scraper4 main site
# (scraper4.py) from a GitHub branch. It bypasses the deployer page entirely:
# download → validate → backup → atomic replace → reload the web app.
#
# Usage (from the PythonAnywhere Bash console, run from ~):
#   bash install_mainsite.sh
#
# Optional env vars:
#   SRC_BRANCH           branch to install from  (default arena/01a06abd-amphp)
#   DEPLOYER_TARGET      explicit scraper4.py path (auto-detected otherwise)
#   FORCE=1              install even when the local version is already
#                        equal to or newer than the branch version
#   DEPLOYER_SOURCE_RAW  override the raw download URL (tests/mirrors)
#   DEPLOYER_GITHUB_API  GitHub API base for the primary download (default https://api.github.com)
#   DEPLOYER_REPO        repository owner/name  (default fazilatma/amphp)
#
# Existing file is always backed up: scraper4.py.bak.<timestamp> plus
# scraper4.py.bak (the backup the deployer's "بازگشت" button uses).
# ---------------------------------------------------------------------------
set -euo pipefail

SRC_BRANCH="${SRC_BRANCH:-arena/01a06abd-amphp}"
REPO="${DEPLOYER_REPO:-fazilatma/amphp}"
USER_NAME="$(id -un)"
HOME_DIR="$HOME"
GITHUB_API="${DEPLOYER_GITHUB_API:-https://api.github.com}"
RAW_URL="${DEPLOYER_SOURCE_RAW:-https://raw.githubusercontent.com/$REPO/$SRC_BRANCH/scraper4.py}"

# --- locate the target --------------------------------------------------------
TARGET="${DEPLOYER_TARGET:-}"
if [ -z "$TARGET" ]; then
    for cand in "$HOME_DIR/scraper4/scraper4.py" "$HOME_DIR/amphp/scraper4.py" \
                "$HOME_DIR/scraper4.py" "$HOME_DIR/mysite/scraper4.py"; do
        if [ -f "$cand" ]; then TARGET="$cand"; break; fi
    done
fi
[ -n "$TARGET" ] || { echo "خطا: scraper4.py پیدا نشد؛ با DEPLOYER_TARGET=~/scraper4/scraper4.py مسیر را بدهید" >&2; exit 1; }
TARGET="$(readlink -f "$TARGET" 2>/dev/null || printf '%s' "$TARGET")"
PY="$(command -v python3 || true)"
[ -n "$PY" ] || { echo "خطا: python3 پیدا نشد" >&2; exit 1; }

echo "==> فایل هدف:   $TARGET"
echo "==> برنچ مبدأ:  $SRC_BRANCH"

# --- download ------------------------------------------------------------------
TMP="$(mktemp "$HOME_DIR/.scraper4-dl.XXXXXX.py" 2>/dev/null || mktemp /tmp/scraper4-dl.XXXXXX.py)"
trap 'if [ -n "${TMP:-}" ]; then rm -f -- "$TMP"; fi' EXIT
# GitHub contents API (raw media) first — raw.githubusercontent.com is often
# unreachable from PythonAnywhere (curl: (35) SSL_ERROR_SYSCALL).
echo "==> دانلود از GitHub API: $REPO@$SRC_BRANCH/scraper4.py"
if ! curl -fsSL --retry 3 --connect-timeout 20 --max-time 180 \
     -H 'Accept: application/vnd.github.raw' \
     -o "$TMP" "$GITHUB_API/repos/$REPO/contents/scraper4.py?ref=$SRC_BRANCH" 2>/dev/null \
   || [ ! -s "$TMP" ]; then
    echo "==> تلاش مجدد از raw.githubusercontent → $RAW_URL"
    curl -fsSL --retry 3 --connect-timeout 20 --max-time 180 "$RAW_URL" -o "$TMP"
fi

# --- validate + read new version ------------------------------------------------
NEW_VERSION="$("$PY" - "$TMP" <<'PY'
import re, sys, pathlib
s = pathlib.Path(sys.argv[1]).read_text(encoding="utf-8")
compile(s, "scraper4.py", "exec")
missing = [m for m in ("APP_VERSION", "Flask(", '@app.get("/")', "/api/deploy/run") if m not in s]
if missing:
    raise SystemExit("فایل دانلودشده معتبر نیست؛ نشانه‌های ناقص: " + ", ".join(missing))
m = re.search(r'^APP_VERSION\s*=\s*["\']([^"\']+)', s, re.M)
print(m.group(1) if m else "unknown")
PY
)"
echo "==> نسخه دانلودشده: $NEW_VERSION"

# --- local version + no-downgrade guard ----------------------------------------
OLD_VERSION="unknown"
if [ -f "$TARGET" ]; then
    OLD_VERSION="$("$PY" - "$TARGET" <<'PY'
import re, sys, pathlib
s = pathlib.Path(sys.argv[1]).read_text(encoding="utf-8", errors="replace")
m = re.search(r'^APP_VERSION\s*=\s*["\']([^"\']+)', s, re.M)
print(m.group(1) if m else "unknown")
PY
)"
    echo "==> نسخه فعلی: $OLD_VERSION"
    if [ "${FORCE:-0}" != 1 ]; then
        NEWER_OR_EQUAL="$("$PY" - "$OLD_VERSION" "$NEW_VERSION" <<'PY'
import re, sys
def key(v):
    m = re.match(r'^v?(\d+(?:\.\d+){0,3})', v)
    nums = [int(x) for x in m.group(1).split(".")] if m else [0]
    return (nums + [0, 0, 0])[:4]
def vs(a, b):
    ka, kb = key(a), key(b)
    for x, y in zip(ka, kb):
        if x != y:
            return 1 if x > y else -1
    return 0
print(1 if vs(sys.argv[1], sys.argv[2]) >= 0 else 0)
PY
)"
        if [ "$NEWER_OR_EQUAL" = 1 ]; then
            echo "==> نسخه محلی v$OLD_VERSION برابر/جدیدتر از v$NEW_VERSION برنچ $SRC_BRANCH است؛ چیزی نصب نشد."
            echo "    (برای نصب اجباری: FORCE=1 bash install_mainsite.sh)"
            exit 0
        fi
    fi
fi

# --- backup + atomic install -----------------------------------------------------
if [ -f "$TARGET" ]; then
    TS="$(date +%Y%m%d-%H%M%S)"
    cp -p "$TARGET" "$TARGET.bak.$TS"
    cp -p "$TARGET" "$TARGET.bak"
    echo "==> پشتیبان‌ها: $TARGET.bak.$TS و $TARGET.bak (برای دکمه بازگشت دیپلوی‌ر)"
    MODE="$(stat -c '%a' "$TARGET" 2>/dev/null || echo 600)"
else
    MODE=600
fi
chmod "$MODE" "$TMP" 2>/dev/null || true
mv -f "$TMP" "$TARGET"
TMP=""
echo "==> نصب شد: $TARGET (v$NEW_VERSION از برنچ $SRC_BRANCH)"

# --- reload the web app ------------------------------------------------------------
TOKEN_FILE="$HOME_DIR/.pythonanywhere_api_token"
reload_ok=0
if [ -s "$TOKEN_FILE" ]; then
    TOKEN="$(tr -d '[:space:]' < "$TOKEN_FILE")"
    DOMAIN="$(printf '%s' "$USER_NAME" | tr '[:upper:]' '[:lower:]').pythonanywhere.com"
    echo "==> درخواست reload وب‌اپ…"
    CODE="$(curl -sS -o /dev/null -w '%{http_code}' -X POST \
        -H "Authorization: Token $TOKEN" --connect-timeout 20 --max-time 120 \
        "https://www.pythonanywhere.com/api/v0/user/$USER_NAME/webapps/$DOMAIN/reload/" || true)"
    if [ "$CODE" = "200" ] || [ "$CODE" = "201" ]; then
        reload_ok=1
        echo "==> وب‌اپ reload شد ✔"
    else
        echo "==> reload خودکار ناموفق بود (HTTP $CODE); لطفاً در تب Web دکمه Reload را بزنید"
    fi
else
    echo "==> توکن API پیدا نشد؛ لطفاً در تب Web دکمه Reload را بزنید"
fi

# --- hint: is the main site actually wired in the WSGI? ----------------------------
WSGI_WIRED=0
if [ -d /var/www ]; then
    WSGI_FILE="$(find /var/www -maxdepth 1 -type f -name '*_wsgi.py' ! -name '*.bak*' 2>/dev/null | head -n1 || true)"
    if [ -n "$WSGI_FILE" ] && grep -q "from scraper4 import app" "$WSGI_FILE" 2>/dev/null; then
        WSGI_WIRED=1
    fi
fi

echo
echo "✅ نصب کامل شد: scraper4.py → v$NEW_VERSION (برنچ $SRC_BRANCH)"
if [ "$reload_ok" != 1 ]; then
    echo "⚠️  بعد از نصب، در PythonAnywhere تب Web → دکمه Reload را بزنید تا نسخه جدید اجرا شود."
fi
if [ "$WSGI_WIRED" != 1 ]; then
    echo "⚠️  نکته: در فایل WSGI اصلی، سایت scraper4 هنوز mount نشده است."
    echo "    اگر آدرس اصلی سایت (بدون /deployer) کار نمی‌کند، اجرا کنید:"
    echo "        cd ~ && curl -fsSL -o install_pythonanywhere.sh https://raw.githubusercontent.com/$REPO/$SRC_BRANCH/install_pythonanywhere.sh && bash install_pythonanywhere.sh"
    echo "    (این کار مونت دیپلوی‌ر در /deployer را حفظ می‌کند.)"
fi
