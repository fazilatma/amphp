#!/usr/bin/env bash
# run_scraper4.sh — install AND update to latest, Termux (phone) or VPS.
# Detects the machine. One command does clone/fetch, copy, restart.
#
# Termux:  bash ~/amphp/run_scraper4.sh
# VPS:     bash /opt/amphp/run_scraper4.sh
#
# Phone:   http://127.0.0.1:8000/   and  http://127.0.0.1:8001/
# VPS:     http://37.32.5.36/put/   and  http://37.32.5.36/deploy/
set -u
umask 077

BRANCH="${BRANCH:-arena/01a06ac3-amphp}"
REPO_URL="https://github.com/fazilatma/amphp.git"
CMD="${1:-update}"

is_termux() {
  [ -n "${TERMUX_VERSION:-}" ] || [ -d /data/data/com.termux/files/usr ] || [ "${PREFIX:-}" = "/data/data/com.termux/files/usr" ]
}
is_vps() {
  [ "$(id -u 2>/dev/null || echo 1)" = "0" ] && command -v systemctl >/dev/null 2>&1 && [ -d /opt ]
}
fail() { echo "ERROR: $*" >&2; exit 1; }

if is_termux; then
  ROLE="termux"; SRC="${HOME}/amphp"; RUN="${HOME}/scraper4"
elif is_vps; then
  ROLE="vps"; SRC="/opt/amphp"; RUN="/opt/scraper4"
else
  ROLE="linux"; SRC="${HOME}/amphp"; RUN="${HOME}/scraper4"
fi
VENV="${RUN}/venv"
PY="${VENV}/bin/python"

fetch_latest() {
  command -v git >/dev/null 2>&1 || fail "git is missing"
  mkdir -p "$(dirname "$SRC")"
  if [ ! -d "${SRC}/.git" ]; then
    echo "Install: clone ${BRANCH} -> ${SRC}"
    git clone --depth 1 --branch "$BRANCH" "$REPO_URL" "$SRC" || fail "git clone failed (need network)"
    return 0
  fi
  echo "Update: fetch origin ${BRANCH}"
  if git -C "$SRC" fetch --depth 1 origin "$BRANCH"; then
    git -C "$SRC" reset --hard FETCH_HEAD
    echo "Git now: $(git -C "$SRC" log -1 --oneline)"
  else
    echo "WARNING: fetch failed — using files already in ${SRC} (offline)."
  fi
}

ensure_venv() {
  mkdir -p "$RUN"
  command -v python3 >/dev/null 2>&1 || fail "python3 is missing"
  if [ ! -x "$PY" ]; then
    python3 -m venv "$VENV" || fail "venv create failed"
  fi
  if ! "$PY" -c "import flask,gunicorn,requests,bs4,lxml" 2>/dev/null; then
    echo "pip: flask gunicorn requests beautifulsoup4 lxml (network this once)"
    "$PY" -m pip install flask gunicorn requests beautifulsoup4 lxml || fail "pip install failed"
  fi
}

sync_code() {
  [ -f "${SRC}/scraper4.py" ] || fail "scraper4 missing in ${SRC}"
  [ -f "${SRC}/deployer4.py" ] || fail "deployer4 missing in ${SRC}"
  cp -a "${SRC}/"*.py "$RUN/"
  find "$RUN" -name '*.pyc' -delete 2>/dev/null || true
  find "$RUN" -type d -name __pycache__ -exec rm -rf {} + 2>/dev/null || true
  "$PY" -m py_compile "${RUN}/scraper4.py" "${RUN}/deployer4.py" || fail "py_compile failed"
  "$PY" - <<'PY'
import re, pathlib, os
run=os.environ.get("S4_RUN",".")
text=pathlib.Path(run,"scraper4.py").read_text(encoding="utf-8", errors="replace")
m=re.search(r'^APP_VERSION\s*=\s*["\']([^"\']+)', text, re.M)
d=re.search(r'^DEPLOYER_VERSION\s*=\s*["\']([^"\']+)', pathlib.Path(run,"deployer4.py").read_text(encoding="utf-8", errors="replace"), re.M)
print("Installed scraper", m.group(1) if m else "?", "deployer", d.group(1) if d else "?")
PY
}

termux_pkgs() {
  command -v python3 >/dev/null 2>&1 && command -v git >/dev/null 2>&1 && return 0
  command -v pkg >/dev/null 2>&1 || fail "Termux pkg not found"
  pkg update -y
  pkg install -y python git
}

pid_for() { pgrep -f "gunicorn.*${1}:application" 2>/dev/null | head -n1 || true; }

stop_local() {
  pkill -f "gunicorn.*scraper4:application" 2>/dev/null || true
  pkill -f "gunicorn.*deployer4:application" 2>/dev/null || true
  sleep 1
}

start_local() {
  stop_local
  export PYTHONUNBUFFERED=1
  export SCRAPER_RUNTIME=vps
  export SCRAPER_URL_PREFIX="${SCRAPER_URL_PREFIX:-}"
  export SCRAPER_AUTO_UPDATE=0
  export DEPLOYER_AUTO_UPDATE=0
  export DEPLOYER_AUTO_START=0
  export DEPLOYER_TARGET="${RUN}/scraper4.py"
  export DEPLOYER_GIT_DIR="$SRC"
  cd "$RUN" || fail "cd ${RUN}"
  nohup "$PY" -m gunicorn --bind 0.0.0.0:8000 --workers 1 --threads 4 --timeout 0 --graceful-timeout 30 scraper4:application \
    >"${RUN}/scraper4.log" 2>&1 &
  nohup "$PY" -m gunicorn --bind 0.0.0.0:8001 --workers 1 --threads 2 --timeout 120 deployer4:application \
    >"${RUN}/deployer4.log" 2>&1 &
  sleep 2
  echo "Scraper  PID $(pid_for scraper4 || echo '?')  ${RUN}/scraper4.log"
  echo "Deployer PID $(pid_for deployer4 || echo '?')  ${RUN}/deployer4.log"
}

print_phone_urls() {
  echo "============================================================"
  echo "PHONE browser (same device):"
  echo "  Scraper  http://127.0.0.1:8000/"
  echo "  Deployer http://127.0.0.1:8001/"
  LAN="$(ip -4 -o addr show 2>/dev/null | awk '!/127.0.0.1/ {print $4}' | cut -d/ -f1 | head -n1 || true)"
  if [ -n "${LAN:-}" ]; then
    echo "Other device on same Wi-Fi:"
    echo "  Scraper  http://${LAN}:8000/"
    echo "  Deployer http://${LAN}:8001/"
  fi
  echo "============================================================"
}

install_or_update_vps() {
  if ! command -v git >/dev/null 2>&1 || ! command -v python3 >/dev/null 2>&1; then
    command -v apt-get >/dev/null 2>&1 && apt-get install -y git python3 python3-venv python3-pip apache2
  fi
  a2enmod proxy proxy_http headers >/dev/null 2>&1 || true
  mkdir -p /opt /opt/scraper4
  SRC=/opt/amphp; RUN=/opt/scraper4; VENV="${RUN}/venv"; PY="${VENV}/bin/python"
  fetch_latest
  if [ "${S4_REEXEC:-0}" != 1 ] && [ -f /opt/amphp/run_scraper4.sh ]; then
    echo "Re-run latest script from /opt/amphp"
    export S4_REEXEC=1
    exec bash /opt/amphp/run_scraper4.sh "$CMD"
  fi
  ensure_venv
  S4_RUN="$RUN" sync_code
  cp -a /opt/amphp/deploy/scraper4.service /etc/systemd/system/scraper4.service
  cp -a /opt/amphp/deploy/deployer4.service /etc/systemd/system/deployer4.service
  cp -a /opt/amphp/deploy/scraper4.apache.conf /etc/apache2/conf-available/scraper4-put.conf
  a2enconf scraper4-put >/dev/null 2>&1 || true
  systemctl daemon-reload
  systemctl enable scraper4 deployer4
  systemctl restart scraper4 deployer4
  systemctl reload apache2 || true
  echo "============================================================"
  echo "VPS:"
  echo "  Scraper  http://37.32.5.36/put/"
  echo "  Deployer http://37.32.5.36/deploy/"
  echo "  Apache / stays PHP"
  echo "============================================================"
  curl -sS http://127.0.0.1:8000/health || true; echo
  curl -sS http://127.0.0.1:8001/health || true; echo
}

do_update() {
  if [ "$ROLE" = "vps" ]; then
    install_or_update_vps
    return
  fi
  [ "$ROLE" = "termux" ] && termux_pkgs
  fetch_latest
  if [ "${S4_REEXEC:-0}" != 1 ] && [ -f "${SRC}/run_scraper4.sh" ]; then
    echo "Re-run latest script from ${SRC}"
    export S4_REEXEC=1
    exec bash "${SRC}/run_scraper4.sh" "$CMD"
  fi
  ensure_venv
  S4_RUN="$RUN" sync_code
  start_local
  print_phone_urls
}

status_local() {
  echo "role=${ROLE} src=${SRC} run=${RUN}"
  echo -n "scraper:  "; pid_for scraper4 || echo stopped
  echo -n "deployer: "; pid_for deployer4 || echo stopped
  if is_vps; then systemctl --no-pager --full status scraper4 deployer4 | sed -n '1,24p' || true; fi
}

case "$CMD" in
  stop)
    if is_vps; then systemctl stop scraper4 deployer4 || true; fi
    stop_local
    ;;
  status) status_local ;;
  start|install|update|"") do_update ;;
  *)
    echo "Usage: bash run_scraper4.sh [update|stop|status]"
    exit 1
    ;;
esac
