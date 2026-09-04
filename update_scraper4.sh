#!/usr/bin/env bash
# ============================================================================
#  Scraper4 — Console Updater (PythonAnywhere / any Linux host)
#  Downloads the newest scraper4.py from GitHub, validates it, backs up the
#  current file, installs atomically, fixes the in-app deploy branch and
#  reloads the web app.
#
#  Usage (paste ONE line in the PythonAnywhere Bash console):
#    curl -fsSL https://raw.githubusercontent.com/fazilatma/amphp/arena/01a06927-amphp/update_scraper4.sh | bash
#
#  Or:  bash update_scraper4.sh [--force] [--keep N] [--rollback] [--no-reload]
# ============================================================================
set -Eeuo pipefail
umask 077

REPO="${SCRAPER_REPO:-fazilatma/amphp}"
BRANCH="${SCRAPER_BRANCH:-arena/01a06927-amphp}"
FILE="${SCRAPER_FILE:-scraper4.py}"
SOURCE_URL="${SCRAPER_SOURCE_URL:-https://raw.githubusercontent.com/$REPO/$BRANCH/$FILE}"

FORCE=0; KEEP=5; ROLLBACK=0; DO_RELOAD=1
while [ $# -gt 0 ]; do
  case "$1" in
    --force)     FORCE=1 ;;
    --keep)      KEEP="${2:-5}"; shift ;;
    --rollback)  ROLLBACK=1 ;;
    --no-reload) DO_RELOAD=0 ;;
    --branch)    BRANCH="${2:?--branch needs a value}"; shift ;;
    -h|--help)   grep '^#' "$0" | sed 's/^# \{0,1\}//'; exit 0 ;;
    *) echo "Unknown option: $1" >&2; exit 2 ;;
  esac
  shift
done

USER_NAME="$(id -un)"
USER_LOWER="$(printf '%s' "$USER_NAME" | tr '[:upper:]' '[:lower:]')"
HOME_DIR="${HOME:-$(getent passwd "$USER_NAME" | cut -d: -f6)}"

log(){ printf '%s %s\n' "[update]" "$*"; }
fail(){ printf 'ERROR: %s\n' "$*" >&2; exit 1; }

TMP=""; AUTHF=""; RESPF=""
cleanup(){
  # NOTE: must always return 0, otherwise this EXIT trap overrides the
  # script's real exit status when the optional temp files were never made.
  for f in "${TMP:-}" "${AUTHF:-}" "${RESPF:-}"; do
    if [ -n "$f" ]&&[ -e "$f" ]; then rm -f "$f"; fi
  done
  return 0
}
trap cleanup EXIT

# ---------------------------------------------------------------- locate app
find_app_file(){
  if [ -n "${SCRAPER_APP_DIR:-}" ]&&[ -f "$SCRAPER_APP_DIR/$FILE" ]; then
    printf '%s' "$SCRAPER_APP_DIR/$FILE"; return 0
  fi
  if [ -f "$HOME_DIR/scraper4/$FILE" ]; then
    printf '%s' "$HOME_DIR/scraper4/$FILE"; return 0
  fi
  find "$HOME_DIR" -maxdepth 3 -type f -name "$FILE" -not -path '*/venv/*' -not -path '*/.git/*' -print 2>/dev/null | head -n 1
}

APP_FILE="$(find_app_file||true)"
if [ -z "$APP_FILE" ]; then
  APP_DIR="$HOME_DIR/scraper4"; mkdir -p "$APP_DIR"
  APP_FILE="$APP_DIR/$FILE"
  log "No existing install found; will create $APP_FILE"
else
  APP_DIR="$(dirname "$APP_FILE")"
  log "Found install: $APP_FILE"
fi
DATA_FILE="${SCRAPER_DATA_FILE:-$APP_DIR/scraper4_data.json}"

# ------------------------------------------------------------- pick python
PY="$APP_DIR/venv/bin/python"
[ -x "$PY" ]||PY="$(command -v python3||true)"
[ -n "$PY" ]||fail "python3 not found on PATH."
log "Using interpreter: $PY ($($PY -V 2>&1))"

newest_backup(){ ls -1t "$APP_DIR"/"$FILE".*.bak 2>/dev/null | head -n 1; }

# --------------------------------------------------------------- rollback
if [ "$ROLLBACK" = 1 ]; then
  BAK="$(newest_backup||true)"
  [ -n "$BAK" ]||fail "No backup file ($FILE.*.bak) found in $APP_DIR to roll back to."
  cp -p "$BAK" "$APP_FILE"; chmod 600 "$APP_FILE"
  log "Rolled back to $(basename "$BAK")"
  "$PY" -m py_compile "$APP_FILE"&&log "Rolled-back source compiles cleanly."
  exit 0
fi

# ------------------------------------------------------------- download
log "Source: $SOURCE_URL"
TMP="$(mktemp "${TMPDIR:-/tmp}/scraper4-update-XXXXXX.py")"
HTTP_CODE="$(curl -sS -L --retry 3 --retry-delay 2 --connect-timeout 20 --max-time 180 \
  -o "$TMP" -w '%{http_code}' "$SOURCE_URL"||echo 000)"
[ "$HTTP_CODE" = 200 ]||fail "Download failed (HTTP $HTTP_CODE) from $SOURCE_URL"
[ -s "$TMP" ]||fail "Downloaded file is empty."
log "Downloaded $(wc -c <"$TMP") bytes."

# ------------------------------------------------------------- validate
"$PY" - "$TMP" <<'PY'
import ast,pathlib,sys
p=pathlib.Path(sys.argv[1]); s=p.read_text(encoding="utf-8")
need=("APP_VERSION","Flask(","/api/scrape","/api/deploy/run")
missing=[x for x in need if x not in s]
if missing: raise SystemExit("Invalid download; missing markers: "+", ".join(missing))
ast.parse(s,filename=str(p))
ver="?"
for line in s.splitlines():
    if line.startswith("APP_VERSION"):
        ver=line.split("=",1)[1].strip().strip('"\''); break
print("Downloaded source is valid Python. APP_VERSION =",ver)
PY

# ------------------------------------------------------- install / backup
if [ -f "$APP_FILE" ]; then
  NEW_SUM="$($PY -c 'import hashlib,sys;print(hashlib.sha256(open(sys.argv[1],"rb").read()).hexdigest())' "$TMP")"
  OLD_SUM="$($PY -c 'import hashlib,sys;print(hashlib.sha256(open(sys.argv[1],"rb").read()).hexdigest())' "$APP_FILE")"
  log "Installed sha256: $OLD_SUM"
  log "Incoming  sha256: $NEW_SUM"
  if [ "$NEW_SUM" = "$OLD_SUM" ]&&[ "$FORCE" != 1 ]; then
    log "Already up to date — nothing to install. (use --force to reinstall anyway)"
  else
    BAK="$APP_FILE.$(date +%Y%m%d-%H%M%S).bak"
    cp -p "$APP_FILE" "$BAK"
    log "Backup written: $BAK"
    STAGE="$APP_FILE.new.$$"
    cp "$TMP" "$STAGE"; chmod 600 "$STAGE"
    mv "$STAGE" "$APP_FILE"; TMP=""
    log "New version installed atomically."
  fi
else
  STAGE="$APP_FILE.new.$$"
  cp "$TMP" "$STAGE"; chmod 600 "$STAGE"
  mv "$STAGE" "$APP_FILE"; TMP=""
  log "Fresh install written to $APP_FILE"
fi

# trim old backups (always keep the newest $KEEP)
if [ "$KEEP" -ge 0 ]; then
  REMOVED=0
  while read -r old; do
    [ -n "$old" ]||continue
    rm -f "$old"&&REMOVED=$((REMOVED+1))
  done <<EOF
$(ls -1t "$APP_DIR"/"$FILE".*.bak 2>/dev/null | tail -n +$((KEEP+1)))
EOF
  [ "$REMOVED" -gt 0 ]&&log "Pruned $REMOVED old backup(s), keeping newest $KEEP."
fi

# ------------------------------------------------------ post-install checks
"$PY" -m py_compile "$APP_FILE"||fail "New source failed py_compile."
rm -rf "$APP_DIR/__pycache__"
"$PY" - "$APP_DIR" <<'PY'
import pathlib,sys
sys.path.insert(0,str(pathlib.Path(sys.argv[1]).resolve()))
import scraper4
routes={r.rule for r in scraper4.app.url_map.iter_rules()}
need={"/","/health","/api/scrape","/api/deploy/run"}
if need-routes: raise RuntimeError("Missing routes: "+", ".join(need-routes))
assert scraper4.app.test_client().get("/health").status_code==200
print("Smoke test passed. Running version:",scraper4.APP_VERSION)
PY

# ----------------------------------- keep the in-app updater on this branch
"$PY" - "$DATA_FILE" "$REPO" "$BRANCH" "$FILE" <<'PY'
import json,os,pathlib,sys
df=pathlib.Path(sys.argv[1]); repo,branch,path=sys.argv[2],sys.argv[3],sys.argv[4]
data={}
if df.exists():
    try:
        x=json.loads(df.read_text(encoding="utf-8")); data=x if isinstance(x,dict) else {}
    except Exception: data={}
old=data.get("deploy") if isinstance(data.get("deploy"),dict) else {}
old.update({"repo":repo,"branch":branch,"path":path})
data["deploy"]=old
tmp=df.with_suffix(".json.tmp"); tmp.write_text(json.dumps(data,ensure_ascii=False,indent=2),encoding="utf-8"); tmp.replace(df)
os.chmod(df,0o600)
print("Saved deploy target in data file:",repo,"@",branch,"->",path)
PY

# ------------------------------------------------------------------ reload
if [ "$DO_RELOAD" = 1 ]; then
  DOMAIN="${SCRAPER_DOMAIN:-${USER_LOWER}.pythonanywhere.com}"
  TOKEN_FILE="$HOME_DIR/.pythonanywhere_api_token"
  RELOADED=0
  if [ -s "$TOKEN_FILE" ]; then
    chmod 600 "$TOKEN_FILE" 2>/dev/null||true
    AUTHF="$(mktemp)"; RESPF="$(mktemp)"; chmod 600 "$AUTHF"
    printf 'header = "Authorization: Token %s"\n' "$(tr -d '[:space:]' <"$TOKEN_FILE")" >"$AUTHF"
    API="https://www.pythonanywhere.com/api/v0/user/$USER_NAME/webapps/$DOMAIN/reload/"
    for n in 1 2 3; do
      ST="$(curl --config "$AUTHF" -sS -o "$RESPF" -w '%{http_code}' --connect-timeout 20 --max-time 120 -X POST "$API"||echo 000)"
      if [ "$ST" = 200 ]||[ "$ST" = 201 ]; then RELOADED=1; log "Web app reloaded via API (HTTP $ST)."; break; fi
      log "Reload attempt $n/3 returned HTTP $ST."
      sleep 4
    done
  else
    log "No PythonAnywhere API token at $TOKEN_FILE; trying WSGI touch."
  fi
  if [ "$RELOADED" != 1 ]; then
    WSGI=""
    for f in "/var/www/${USER_LOWER}_pythonanywhere_com_wsgi.py" "/var/www/${USER_NAME}_pythonanywhere_com_wsgi.py"; do
      [ -f "$f" ]&&[ -w "$f" ]&&{ WSGI="$f"; break; }
    done
    [ -z "$WSGI" ]&&WSGI="$(find /var/www -maxdepth 1 -type f -name '*_pythonanywhere_com_wsgi.py' -writable -print 2>/dev/null|head -n1||true)"
    if [ -n "$WSGI" ]; then
      touch "$WSGI"; RELOADED=1; log "Touched WSGI to trigger reload: $WSGI"
    else
      log "WARNING: could not reload automatically. Open the Web tab and press the green Reload button."
    fi
  fi

  if [ "$RELOADED" = 1 ]; then
    LIVE=000
    for n in $(seq 1 12); do
      sleep 4
      LIVE="$(curl -sS -o /dev/null -w '%{http_code}' --connect-timeout 15 --max-time 30 "https://$DOMAIN/health" 2>/dev/null||echo 000)"
      [ "$LIVE" = 200 ]&&break
      log "Health check $n/12: HTTP $LIVE"
    done
    if [ "$LIVE" = 200 ]; then
      log "Live health OK: https://$DOMAIN/health"
      curl -sS --connect-timeout 15 --max-time 30 "https://$DOMAIN/health" 2>/dev/null||true
      echo
    else
      log "WARNING: health check returned HTTP $LIVE — inspect /var/log/${DOMAIN}.error.log"
    fi
  fi
fi

echo "============================================================"
echo " UPDATE FINISHED"
echo " Repo/branch : $REPO @ $BRANCH"
echo " Application : $APP_FILE"
echo " Data file   : $DATA_FILE"
echo " Backups kept: newest $KEEP in $APP_DIR"
echo "============================================================"
