#!/usr/bin/env bash
# run_scraper4.sh — one installer/runner for Termux (phone, offline) and VPS.
# Detects the host. Phone binds 0.0.0.0 and needs no Apache/systemd.
# VPS keeps Apache PHP on / and proxies /put/ + /deploy/.
#
# Termux (phone browser after start):
#   Scraper  http://127.0.0.1:8000/
#   Deployer http://127.0.0.1:8001/
# VPS:
#   Scraper  http://37.32.5.36/put/
#   Deployer http://37.32.5.36/deploy/
set -u
umask 077

BRANCH="${BRANCH:-arena/01a06ac3-amphp}"
REPO_URL="https://github.com/fazilatma/amphp.git"
CMD="${1:-start}"

is_termux() {
  [ -n "${TERMUX_VERSION:-}" ] || [ -d /data/data/com.termux/files/usr ] || [ "${PREFIX:-}" = "/data/data/com.termux/files/usr" ]
}

is_vps() {
  [ "$(id -u 2>/dev/null || echo 1)" = "0" ] && command -v systemctl >/dev/null 2>&1 && [ -d /opt ]
}

fail() { echo "ERROR: $*" >&2; exit 1; }

if is_termux; then
  ROLE="termux"
  SRC="${HOME}/amphp"
  RUN="${HOME}/scraper4"
  PY_PKGS="python git"
elif is_vps; then
  ROLE="vps"
  SRC="/opt/amphp"
  RUN="/opt/scraper4"
else
  ROLE="linux"
  SRC="${HOME}/amphp"
  RUN="${HOME}/scraper4"
fi
VENV="${RUN}/venv"
PY="${VENV}/bin/python"

ensure_src() {
  if [ -d "${SRC}/.git" ]; then
    echo "Repo already at ${SRC} (offline-ok)."
    return 0
  fi
  command -v git >/dev/null 2>&1 || fail "git is missing; install it while online, then retry."
  echo "Cloning ${REPO_URL} (${BRANCH}) -> ${SRC}"
  mkdir -p "$(dirname "$SRC")"
  git clone --depth 1 --branch "$BRANCH" "$REPO_URL" "$SRC" || fail "git clone failed (need network once)."
}

ensure_venv() {
  mkdir -p "$RUN"
  if [ ! -x "$PY" ]; then
    command -v python3 >/dev/null 2>&1 || fail "python3 is missing."
    python3 -m venv "$VENV" || fail "venv create failed."
  fi
  if ! "$PY" -c "import flask,gunicorn,requests,bs4,lxml" 2>/dev/null; then
    echo "Installing Python packages into venv (needs network this once)..."
    "$PY" -m pip install flask gunicorn requests beautifulsoup4 lxml || fail "pip install failed."
  else
    echo "Python packages already in venv (offline-ok)."
  fi
}

sync_code() {
  [ -f "${SRC}/scraper4.py" ] || fail "scraper4 missing in ${SRC}"
  [ -f "${SRC}/deployer4.py" ] || fail "deployer4 missing in ${SRC}"
  cp -a "${SRC}/"*.py "$RUN/"
  "$PY" -m py_compile "${RUN}/scraper4.py" "${RUN}/deployer4.py" || fail "py_compile failed."
}

termux_pkgs() {
  command -v python3 >/dev/null 2>&1 && command -v git >/dev/null 2>&1 && return 0
  command -v pkg >/dev/null 2>&1 || fail "Termux pkg not found."
  pkg update -y
  pkg install -y python git
}

pid_for() {
  # $1 = module name fragment
  pgrep -f "gunicorn.*${1}:application" 2>/dev/null | head -n1 || true
}

stop_local() {
  pkill -f "gunicorn.*scraper4:application" 2>/dev/null || true
  pkill -f "gunicorn.*deployer4:application" 2>/dev/null || true
  sleep 1
  echo "Stopped local gunicorn (if it was running)."
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
  echo "Scraper  PID $(pid_for scraper4 || echo '?')  log ${RUN}/scraper4.log"
  echo "Deployer PID $(pid_for deployer4 || echo '?')  log ${RUN}/deployer4.log"
}

print_phone_urls() {
  echo "============================================================"
  echo "PHONE (same device browser / Termux):"
  echo "  Scraper  http://127.0.0.1:8000/"
  echo "  Deployer http://127.0.0.1:8001/"
  LAN="$(ip -4 -o addr show 2>/dev/null | awk '!/127.0.0.1/ {print $4}' | cut -d/ -f1 | head -n1 || true)"
  if [ -n "${LAN:-}" ]; then
    echo "Other device on same Wi-Fi:"
    echo "  Scraper  http://${LAN}:8000/"
    echo "  Deployer http://${LAN}:8001/"
  fi
  echo "Offline after this: airplane mode is fine; files stay in ${RUN}"
  echo "============================================================"
}

install_vps() {
  command -v apt-get >/dev/null 2>&1 && apt-get install -y git python3 python3-venv python3-pip apache2
  a2enmod proxy proxy_http headers >/dev/null 2>&1 || true
  mkdir -p /opt /opt/scraper4
  if [ -d /opt/amphp/.git ]; then
    git -C /opt/amphp fetch --depth 1 origin "$BRANCH"
    git -C /opt/amphp reset --hard FETCH_HEAD
  else
    git clone --depth 1 --branch "$BRANCH" "$REPO_URL" /opt/amphp
  fi
  SRC=/opt/amphp
  RUN=/opt/scraper4
  VENV="${RUN}/venv"
  PY="${VENV}/bin/python"
  ensure_venv
  sync_code
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
  curl -sS http://127.0.0.1:8000/health || true
  echo
  curl -sS http://127.0.0.1:8001/health || true
  echo
}

status_local() {
  echo "role=${ROLE} src=${SRC} run=${RUN}"
  echo -n "scraper:  "; pid_for scraper4 || echo stopped
  echo -n "deployer: "; pid_for deployer4 || echo stopped
  if is_vps; then
    systemctl --no-pager --full status scraper4 deployer4 | sed -n '1,24p' || true
  fi
}

case "$CMD" in
  stop)
    if is_vps; then systemctl stop scraper4 deployer4 || true; fi
    stop_local
    ;;
  status)
    status_local
    ;;
  start|install|"")
    if [ "$ROLE" = "vps" ]; then
      install_vps
    else
      [ "$ROLE" = "termux" ] && termux_pkgs
      ensure_src
      ensure_venv
      sync_code
      start_local
      print_phone_urls
    fi
    ;;
  *)
    echo "Usage: bash run_scraper4.sh [start|stop|status]"
    exit 1
    ;;
esac
