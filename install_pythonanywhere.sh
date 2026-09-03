#!/usr/bin/env bash
set -Eeuo pipefail
umask 077

EXPECTED_USERNAME="Fazilatma"
USERNAME="$(id -un)"
HOME_DIR="$HOME"
DOMAIN="${USERNAME}.pythonanywhere.com"
APP_DIR="${HOME_DIR}/scraper4"
APP_FILE="${APP_DIR}/scraper4.py"
DATA_FILE="${APP_DIR}/scraper4_data.json"
PASSWORD_FILE="${APP_DIR}/admin_password.txt"
WSGI_FILE="/var/www/${USERNAME}_pythonanywhere_com_wsgi.py"
REPOSITORY="fazilatma/amphp"
BRANCH="arena/01a0640f-amphp"
REMOTE_FILE="scraper4.py"
SOURCE_URL="https://raw.githubusercontent.com/${REPOSITORY}/${BRANCH}/${REMOTE_FILE}"
API_BASE="https://www.pythonanywhere.com/api/v0/user/${USERNAME}"
AUTH_FILE=""
RESPONSE_FILE=""
DOWNLOAD_FILE=""

cleanup() {
    unset PA_API_TOKEN SCRAPER_PASSWORD WSGI_PASSWORD 2>/dev/null || true
    [ -z "${AUTH_FILE:-}" ] || rm -f "$AUTH_FILE"
    [ -z "${RESPONSE_FILE:-}" ] || rm -f "$RESPONSE_FILE"
    [ -z "${DOWNLOAD_FILE:-}" ] || rm -f "$DOWNLOAD_FILE"
}
trap cleanup EXIT

echo "============================================================"
echo "Scraper4 installer for PythonAnywhere account: $EXPECTED_USERNAME"
echo "============================================================"

if [ "$USERNAME" != "$EXPECTED_USERNAME" ]; then
    echo "ERROR: Current account is '$USERNAME', but '$EXPECTED_USERNAME' is required."
    echo "Run this in the Bash console belonging to $EXPECTED_USERNAME."
    exit 1
fi

if [ ! -d "$HOME_DIR" ] || [ ! -w "$HOME_DIR" ]; then
    echo "ERROR: Home directory is unavailable or not writable: $HOME_DIR"
    exit 1
fi

mkdir -p "$APP_DIR"
chmod 700 "$APP_DIR"

PYTHON_BIN="$(command -v python3 || true)"
if [ -z "$PYTHON_BIN" ]; then
    echo "ERROR: python3 is not installed."
    exit 1
fi

PY_MAJOR="$($PYTHON_BIN -c 'import sys; print(sys.version_info.major)')"
PY_MINOR="$($PYTHON_BIN -c 'import sys; print(sys.version_info.minor)')"
PYTHON_VERSION="python${PY_MAJOR}${PY_MINOR}"

echo "Account: $USERNAME"
echo "Home: $HOME_DIR"
echo "Python: $PYTHON_BIN ($PYTHON_VERSION)"
echo "Website: https://$DOMAIN"
echo
echo "Paste a NEW PythonAnywhere API token and press Enter."
echo "The token will not be displayed."
IFS= read -r -s -p "PythonAnywhere API token: " PA_API_TOKEN
echo

PA_API_TOKEN="$(printf '%s' "$PA_API_TOKEN" | tr -d '[:space:]')"
if [ -z "$PA_API_TOKEN" ]; then
    echo "ERROR: No API token was entered."
    exit 1
fi
if [[ ! "$PA_API_TOKEN" =~ ^[A-Za-z0-9._-]+$ ]]; then
    echo "ERROR: API token contains invalid characters."
    exit 1
fi

AUTH_FILE="${APP_DIR}/.pa-auth-$$.conf"
printf 'header = "Authorization: Token %s"\n' "$PA_API_TOKEN" > "$AUTH_FILE"
chmod 600 "$AUTH_FILE"
unset PA_API_TOKEN
RESPONSE_FILE="${APP_DIR}/.pa-response-$$.json"

echo "Testing PythonAnywhere API token..."
STATUS="$(curl --config "$AUTH_FILE" --silent --show-error \
    --output "$RESPONSE_FILE" --write-out '%{http_code}' \
    --connect-timeout 20 --max-time 60 "${API_BASE}/webapps/")"
if [ "$STATUS" != "200" ]; then
    echo "ERROR: API authentication failed (HTTP $STATUS)."
    cat "$RESPONSE_FILE" || true
    exit 1
fi

echo "Downloading Scraper4..."
DOWNLOAD_FILE="${APP_DIR}/.scraper4-download-$$.py"
curl --fail --location --silent --show-error --retry 3 --retry-delay 2 \
    --connect-timeout 20 --max-time 180 "$SOURCE_URL" --output "$DOWNLOAD_FILE"
if [ ! -s "$DOWNLOAD_FILE" ]; then
    echo "ERROR: Downloaded file is empty."
    exit 1
fi

echo "Validating downloaded source..."
"$PYTHON_BIN" - "$DOWNLOAD_FILE" <<'PY'
import ast
import pathlib
import sys

path = pathlib.Path(sys.argv[1])
source = path.read_text(encoding="utf-8")
required_markers = (
    "APP_VERSION",
    "Flask(",
    "/api/scrape",
    "/api/deploy/check",
    "/api/deploy/run",
    "/api/deploy/rollback",
)
missing = [marker for marker in required_markers if marker not in source]
if missing:
    raise SystemExit("Invalid Scraper4 source; missing: " + ", ".join(missing))
tree = ast.parse(source, filename=str(path))
version = "unknown"
for node in tree.body:
    if not isinstance(node, (ast.Assign, ast.AnnAssign)):
        continue
    targets = node.targets if isinstance(node, ast.Assign) else [node.target]
    if any(isinstance(target, ast.Name) and target.id == "APP_VERSION" for target in targets):
        value = node.value
        if isinstance(value, ast.Constant) and isinstance(value.value, str):
            version = value.value
        break
print("Source is valid. Version:", version)
PY

if [ -f "$APP_FILE" ]; then
    cp -p "$APP_FILE" "${APP_FILE}.$(date +%Y%m%d-%H%M%S).bak"
fi
mv "$DOWNLOAD_FILE" "$APP_FILE"
DOWNLOAD_FILE=""
chmod 600 "$APP_FILE"

 echo "Installing dependencies..."
"$PYTHON_BIN" -m pip install --user --upgrade flask requests beautifulsoup4
"$PYTHON_BIN" -m py_compile "$APP_FILE"
rm -rf "${APP_DIR}/__pycache__"

if [ -s "$PASSWORD_FILE" ]; then
    SCRAPER_PASSWORD="$(tr -d '\r\n' < "$PASSWORD_FILE")"
else
    SCRAPER_PASSWORD="$($PYTHON_BIN -c 'import secrets; print(secrets.token_urlsafe(32))')"
    printf '%s\n' "$SCRAPER_PASSWORD" > "$PASSWORD_FILE"
    chmod 600 "$PASSWORD_FILE"
fi
if [ -z "$SCRAPER_PASSWORD" ]; then
    echo "ERROR: Could not create administrator password."
    exit 1
fi

echo "Checking web app $DOMAIN..."
STATUS="$(curl --config "$AUTH_FILE" --silent --show-error \
    --output "$RESPONSE_FILE" --write-out '%{http_code}' \
    --connect-timeout 20 --max-time 60 "${API_BASE}/webapps/${DOMAIN}/")"
if [ "$STATUS" = "404" ]; then
    echo "Creating web app..."
    STATUS="$(curl --config "$AUTH_FILE" --silent --show-error \
        --output "$RESPONSE_FILE" --write-out '%{http_code}' --request POST \
        --connect-timeout 20 --max-time 120 \
        --data-urlencode "domain_name=${DOMAIN}" \
        --data-urlencode "python_version=${PYTHON_VERSION}" \
        "${API_BASE}/webapps/")"
    if [ "$STATUS" != "200" ] && [ "$STATUS" != "201" ]; then
        echo "ERROR: Could not create web app (HTTP $STATUS)."
        cat "$RESPONSE_FILE" || true
        exit 1
    fi
elif [ "$STATUS" != "200" ]; then
    echo "ERROR: Could not inspect web app (HTTP $STATUS)."
    cat "$RESPONSE_FILE" || true
    exit 1
else
    echo "Web app already exists."
fi

for ATTEMPT in $(seq 1 30); do
    [ ! -f "$WSGI_FILE" ] || break
    echo "Waiting for WSGI file ($ATTEMPT/30)..."
    sleep 2
done
if [ ! -f "$WSGI_FILE" ]; then
    echo "ERROR: WSGI file was not created: $WSGI_FILE"
    exit 1
fi
if [ ! -w "$WSGI_FILE" ]; then
    echo "ERROR: WSGI file is not writable: $WSGI_FILE"
    exit 1
fi

echo "Writing WSGI configuration..."
export INSTALL_APP_DIR="$APP_DIR"
export INSTALL_DATA_FILE="$DATA_FILE"
export INSTALL_WSGI_FILE="$WSGI_FILE"
export WSGI_PASSWORD="$SCRAPER_PASSWORD"
"$PYTHON_BIN" - <<'PY'
import datetime
import os
import pathlib
import shutil

app_dir = pathlib.Path(os.environ["INSTALL_APP_DIR"]).resolve()
data_file = pathlib.Path(os.environ["INSTALL_DATA_FILE"]).resolve()
wsgi_file = pathlib.Path(os.environ["INSTALL_WSGI_FILE"])
password = os.environ["WSGI_PASSWORD"]
backup = app_dir / ("wsgi-" + datetime.datetime.now().strftime("%Y%m%d-%H%M%S") + ".bak")
if wsgi_file.exists():
    shutil.copy2(wsgi_file, backup)
source = "\n".join((
    "import os",
    "import sys",
    "",
    "APP_DIRECTORY = " + repr(str(app_dir)),
    "if APP_DIRECTORY not in sys.path:",
    "    sys.path.insert(0, APP_DIRECTORY)",
    "",
    "os.environ['SCRAPER_PASSWORD'] = " + repr(password),
    "os.environ['SCRAPER_DATA_FILE'] = " + repr(str(data_file)),
    "",
    "from scraper4 import app as application",
    "",
))
temporary = app_dir / ".wsgi-temporary"
temporary.write_text(source, encoding="utf-8")
shutil.copyfile(temporary, wsgi_file)
temporary.unlink()
print("WSGI written:", wsgi_file)
print("WSGI backup:", backup)
PY
unset INSTALL_APP_DIR INSTALL_DATA_FILE INSTALL_WSGI_FILE WSGI_PASSWORD

echo "Writing Scraper4 deployer configuration..."
"$PYTHON_BIN" - "$DATA_FILE" "$WSGI_FILE" "$REPOSITORY" "$BRANCH" "$REMOTE_FILE" <<'PY'
import json
import pathlib
import sys

data_file = pathlib.Path(sys.argv[1])
wsgi_file = pathlib.Path(sys.argv[2])
repository, branch, remote_file = sys.argv[3:6]
data = {}
if data_file.exists():
    try:
        candidate = json.loads(data_file.read_text(encoding="utf-8"))
        if isinstance(candidate, dict):
            data = candidate
    except Exception:
        data_file.rename(data_file.with_suffix(".json.invalid.bak"))
data.setdefault("profiles", {})
data.setdefault("woocommerce", {"url": "", "consumer_key": "", "consumer_secret": ""})
data.setdefault("network", {"timeout": 25, "gap_ms": 350, "proxy": "", "verify_tls": True})
data.setdefault("last_result", [])
old_deploy = data.get("deploy") if isinstance(data.get("deploy"), dict) else {}
data["deploy"] = {
    "repo": repository,
    "branch": branch,
    "path": remote_file,
    "github_token": old_deploy.get("github_token", ""),
    "reload_file": str(wsgi_file),
}
temporary = data_file.with_suffix(".json.temporary")
temporary.write_text(json.dumps(data, ensure_ascii=False, indent=2), encoding="utf-8")
temporary.replace(data_file)
print("Configuration written:", data_file)
PY
chmod 600 "$DATA_FILE"

echo "Testing Flask application..."
"$PYTHON_BIN" - "$APP_DIR" <<'PY'
import pathlib
import sys

sys.path.insert(0, str(pathlib.Path(sys.argv[1]).resolve()))
import scraper4
required = {
    "/", "/health", "/api/config", "/api/scrape",
    "/api/deploy/check", "/api/deploy/run", "/api/deploy/rollback",
}
routes = {rule.rule for rule in scraper4.app.url_map.iter_rules()}
missing = required - routes
if missing:
    raise RuntimeError("Missing routes: " + ", ".join(sorted(missing)))
response = scraper4.app.test_client().get("/health")
if response.status_code != 200:
    raise RuntimeError("Health test failed: HTTP " + str(response.status_code))
print("Application test passed. Version:", scraper4.APP_VERSION)
PY

echo "Reloading PythonAnywhere web app..."
STATUS="$(curl --config "$AUTH_FILE" --silent --show-error \
    --output "$RESPONSE_FILE" --write-out '%{http_code}' --request POST \
    --connect-timeout 20 --max-time 120 \
    "${API_BASE}/webapps/${DOMAIN}/reload/")"
if [ "$STATUS" != "200" ] && [ "$STATUS" != "201" ]; then
    echo "ERROR: Reload failed (HTTP $STATUS)."
    cat "$RESPONSE_FILE" || true
    exit 1
fi

rm -f "$AUTH_FILE" "$RESPONSE_FILE"
AUTH_FILE=""
RESPONSE_FILE=""

echo "Waiting for live website..."
LIVE_STATUS="000"
for ATTEMPT in $(seq 1 12); do
    sleep 5
    LIVE_STATUS="$(curl --silent --show-error --output /dev/null \
        --write-out '%{http_code}' --connect-timeout 15 --max-time 30 \
        "https://${DOMAIN}/health" || true)"
    [ "$LIVE_STATUS" != "200" ] || break
    echo "Health attempt $ATTEMPT/12: HTTP $LIVE_STATUS"
done

echo
echo "============================================================"
echo "INSTALLATION FINISHED"
echo "============================================================"
echo "Website: https://${DOMAIN}"
echo "Login username: admin"
echo "Login password: ${SCRAPER_PASSWORD}"
echo "Password file: ${PASSWORD_FILE}"
echo "Application: ${APP_FILE}"
echo "Configuration: ${DATA_FILE}"
echo "WSGI: ${WSGI_FILE}"
echo "Live health status: HTTP ${LIVE_STATUS}"
echo "============================================================"
unset SCRAPER_PASSWORD
