#!/usr/bin/env bash
set -Eeuo pipefail
umask 077

EXPECTED_USER="Fazilatma"
USER_NAME="$(id -un)"
USER_LOWER="$(printf '%s' "$USER_NAME" | tr '[:upper:]' '[:lower:]')"
HOME_DIR="$HOME"
DOMAIN="${USER_LOWER}.pythonanywhere.com"
APP_DIR="$HOME_DIR/scraper4"
APP_FILE="$APP_DIR/scraper4.py"
DATA_FILE="$APP_DIR/scraper4_data.json"
PASSWORD_FILE="$APP_DIR/admin_password.txt"
TOKEN_FILE="$HOME_DIR/.pythonanywhere_api_token"
VENV_DIR="$APP_DIR/venv"
REPO="fazilatma/amphp"
BRANCH="arena/01a0640f-amphp"
SOURCE_URL="https://raw.githubusercontent.com/$REPO/$BRANCH/scraper4.py"
API="https://www.pythonanywhere.com/api/v0/user/$USER_NAME"
AUTH=""; RESP=""; DOWN=""

cleanup(){ unset TOKEN ADMIN_PASSWORD APP_DIR_E DATA_FILE_E WSGI_FILE_E PASSWORD_E SITE_E 2>/dev/null||true; [ -z "${AUTH:-}" ]||rm -f "$AUTH"; [ -z "${RESP:-}" ]||rm -f "$RESP"; [ -z "${DOWN:-}" ]||rm -f "$DOWN"; }
trap cleanup EXIT
fail(){ echo "ERROR: $*" >&2; exit 1; }
api_call(){ local method="$1" url="$2"; shift 2; curl --config "$AUTH" --silent --show-error --output "$RESP" --write-out '%{http_code}' --request "$method" --connect-timeout 20 --max-time 120 "$@" "$url"; }

[ "$USER_NAME" = "$EXPECTED_USER" ]||fail "Run this in the $EXPECTED_USER account; current user is $USER_NAME."
[ -d "$HOME_DIR" ]&&[ -w "$HOME_DIR" ]||fail "Home is not writable: $HOME_DIR"
[ -s "$TOKEN_FILE" ]||fail "Token file is missing or empty: $TOKEN_FILE"
chmod 600 "$TOKEN_FILE"
TOKEN="$(tr -d '[:space:]' <"$TOKEN_FILE")"
[[ "$TOKEN" =~ ^[A-Za-z0-9._-]+$ ]]||fail "Token file contains invalid characters."
mkdir -p "$APP_DIR"; chmod 700 "$APP_DIR"
SYSTEM_PY="$(command -v python3||true)"; [ -n "$SYSTEM_PY" ]||fail "python3 not found."
MAJOR="$($SYSTEM_PY -c 'import sys;print(sys.version_info.major)')"; MINOR="$($SYSTEM_PY -c 'import sys;print(sys.version_info.minor)' )"
PY_VERSION="python${MAJOR}${MINOR}"
AUTH="$APP_DIR/.pa-auth-$$"; RESP="$APP_DIR/.pa-response-$$"
printf 'header = "Authorization: Token %s"\n' "$TOKEN" >"$AUTH"; chmod 600 "$AUTH"; unset TOKEN

echo "Testing API authentication..."
STATUS="$(api_call GET "$API/webapps/")"; [ "$STATUS" = 200 ]||{ cat "$RESP"||true; fail "API authentication failed: HTTP $STATUS"; }

echo "Downloading Scraper4..."
DOWN="$APP_DIR/.download-$$.py"
curl -fsSL --retry 3 --connect-timeout 20 --max-time 180 "$SOURCE_URL" -o "$DOWN"
"$SYSTEM_PY" - "$DOWN" <<'PY'
import ast,pathlib,sys
p=pathlib.Path(sys.argv[1]); s=p.read_text(encoding="utf-8")
missing=[x for x in ("APP_VERSION","Flask(","/api/scrape","/api/deploy/run") if x not in s]
if missing: raise SystemExit("Invalid download; missing: "+", ".join(missing))
ast.parse(s,filename=str(p)); print("Downloaded source syntax is valid.")
PY
[ ! -f "$APP_FILE" ]||cp -p "$APP_FILE" "$APP_FILE.$(date +%Y%m%d-%H%M%S).bak"
mv "$DOWN" "$APP_FILE"; DOWN=""; chmod 600 "$APP_FILE"

echo "Creating isolated virtual environment..."
if [ ! -x "$VENV_DIR/bin/python" ]; then "$SYSTEM_PY" -m venv "$VENV_DIR"; fi
VENV_PY="$VENV_DIR/bin/python"; VENV_PIP="$VENV_DIR/bin/pip"
"$VENV_PIP" install --upgrade pip flask requests beautifulsoup4
"$VENV_PY" -c 'import flask,requests,bs4; print("Dependencies OK:",flask.__name__,requests.__version__,bs4.__version__)'
"$VENV_PY" -m py_compile "$APP_FILE"; rm -rf "$APP_DIR/__pycache__"
SITE_PACKAGES="$($VENV_PY -c 'import sysconfig;print(sysconfig.get_paths()["purelib"])')"

if [ -s "$PASSWORD_FILE" ]; then ADMIN_PASSWORD="$(tr -d '\r\n' <"$PASSWORD_FILE")"; else ADMIN_PASSWORD="$($VENV_PY -c 'import secrets;print(secrets.token_urlsafe(32))')"; printf '%s\n' "$ADMIN_PASSWORD" >"$PASSWORD_FILE"; chmod 600 "$PASSWORD_FILE"; fi
[ -n "$ADMIN_PASSWORD" ]||fail "Could not generate admin password."

echo "Checking web app $DOMAIN..."
STATUS="$(api_call GET "$API/webapps/$DOMAIN/")"
if [ "$STATUS" = 404 ]; then
 STATUS="$(api_call POST "$API/webapps/" --data-urlencode "domain_name=$DOMAIN" --data-urlencode "python_version=$PY_VERSION")"
 [[ "$STATUS" = 200||"$STATUS" = 201 ]]||{ cat "$RESP"||true; fail "Web app creation failed: HTTP $STATUS"; }
elif [ "$STATUS" != 200 ]; then cat "$RESP"||true; fail "Web app check failed: HTTP $STATUS"; fi

# Configure PythonAnywhere to use the isolated environment.
STATUS="$(api_call PATCH "$API/webapps/$DOMAIN/" --data-urlencode "virtualenv_path=$VENV_DIR")"
[[ "$STATUS" = 200||"$STATUS" = 201 ]]||{ cat "$RESP"||true; fail "Could not set virtualenv: HTTP $STATUS"; }

find_wsgi(){ local f; for f in "/var/www/${USER_LOWER}_pythonanywhere_com_wsgi.py" "/var/www/${USER_NAME}_pythonanywhere_com_wsgi.py"; do [ ! -f "$f" ]||{ printf '%s' "$f"; return; }; done; find /var/www -maxdepth 1 -type f -name '*_pythonanywhere_com_wsgi.py' -writable -print 2>/dev/null|head -n1; }
WSGI=""
for n in $(seq 1 30); do WSGI="$(find_wsgi||true)"; [ -z "$WSGI" ]||break; echo "Waiting for WSGI ($n/30)..."; sleep 2; done
[ -n "$WSGI" ]&&[ -w "$WSGI" ]||fail "No writable WSGI file found in /var/www."
echo "Using WSGI: $WSGI"

export APP_DIR_E="$APP_DIR" DATA_FILE_E="$DATA_FILE" WSGI_FILE_E="$WSGI" PASSWORD_E="$ADMIN_PASSWORD" SITE_E="$SITE_PACKAGES"
"$VENV_PY" - <<'PY'
import datetime,json,os,pathlib,shutil
app=pathlib.Path(os.environ["APP_DIR_E"]); datafile=pathlib.Path(os.environ["DATA_FILE_E"]); wsgi=pathlib.Path(os.environ["WSGI_FILE_E"]); password=os.environ["PASSWORD_E"]; site=os.environ["SITE_E"]
shutil.copy2(wsgi,app/("wsgi-"+datetime.datetime.now().strftime("%Y%m%d-%H%M%S")+".bak"))
source="\n".join(("import os,site,sys","site.addsitedir("+repr(site)+")","APP_DIRECTORY="+repr(str(app)),"if APP_DIRECTORY not in sys.path: sys.path.insert(0,APP_DIRECTORY)","os.environ['SCRAPER_PASSWORD']="+repr(password),"os.environ['SCRAPER_DATA_FILE']="+repr(str(datafile)),"from scraper4 import app as application",""))
tmp=app/".wsgi.tmp"; tmp.write_text(source,encoding="utf-8"); shutil.copyfile(tmp,wsgi); tmp.unlink()
data={}
if datafile.exists():
 try:
  x=json.loads(datafile.read_text(encoding="utf-8")); data=x if isinstance(x,dict) else {}
 except Exception: pass
data.setdefault("profiles",{}); data.setdefault("woocommerce",{"url":"","consumer_key":"","consumer_secret":""}); data.setdefault("network",{"timeout":25,"gap_ms":350,"proxy":"","verify_tls":True}); data.setdefault("last_result",[])
old=data.get("deploy") if isinstance(data.get("deploy"),dict) else {}
data["deploy"]={"repo":"fazilatma/amphp","branch":"arena/01a0640f-amphp","path":"scraper4.py","github_token":old.get("github_token",""),"reload_file":str(wsgi)}
tmp=datafile.with_suffix(".json.tmp"); tmp.write_text(json.dumps(data,ensure_ascii=False,indent=2),encoding="utf-8"); tmp.replace(datafile)
PY
unset APP_DIR_E DATA_FILE_E WSGI_FILE_E PASSWORD_E SITE_E; chmod 600 "$DATA_FILE"

"$VENV_PY" - "$APP_DIR" <<'PY'
import pathlib,sys
sys.path.insert(0,str(pathlib.Path(sys.argv[1]).resolve())); import scraper4
routes={r.rule for r in scraper4.app.url_map.iter_rules()}; need={"/","/health","/api/scrape","/api/deploy/run"}
if need-routes: raise RuntimeError("Missing routes: "+", ".join(need-routes))
assert scraper4.app.test_client().get("/health").status_code==200
print("Local Flask test passed:",scraper4.APP_VERSION)
PY

STATUS="$(api_call POST "$API/webapps/$DOMAIN/reload/")"
[[ "$STATUS" = 200||"$STATUS" = 201 ]]||{ cat "$RESP"||true; fail "Reload failed: HTTP $STATUS"; }
rm -f "$AUTH" "$RESP"; AUTH=""; RESP=""
LIVE=000
for n in $(seq 1 12); do sleep 5; LIVE="$(curl -sS -o /dev/null -w '%{http_code}' --connect-timeout 15 --max-time 30 "https://$DOMAIN/health"||true)"; [ "$LIVE" != 200 ]||break; echo "Health $n/12: HTTP $LIVE"; done

echo "============================================================"
echo "INSTALLATION FINISHED"
echo "Website: https://$DOMAIN"
echo "Login username: admin"
echo "Login password: $ADMIN_PASSWORD"
echo "Password file: $PASSWORD_FILE"
echo "Application: $APP_FILE"
echo "Virtualenv: $VENV_DIR"
echo "WSGI: $WSGI"
echo "Live health: HTTP $LIVE"
echo "============================================================"
unset ADMIN_PASSWORD
