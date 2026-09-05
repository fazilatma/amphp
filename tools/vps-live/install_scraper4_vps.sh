#!/usr/bin/env bash
# Install Scraper4 VPS edition (Python port of scraper4.php 10.123) on this server.
# Run ON THE VPS as root:
#   bash tools/vps-live/install_scraper4_vps.sh
set -euo pipefail

APP_DIR="${SCRAPER_DIR:-/opt/scraper4}"
REPO_DIR="$(cd "$(dirname "$0")/../.." && pwd)"
SRC="${REPO_DIR}/scraper4.py"
PY="${PYTHON:-python3}"

if [[ $EUID -ne 0 ]]; then
  echo "Run as root on the VPS." >&2
  exit 1
fi
if [[ ! -f "$SRC" ]]; then
  echo "Missing $SRC" >&2
  exit 1
fi

export DEBIAN_FRONTEND=noninteractive
apt-get update -y
apt-get install -y python3 python3-pip python3-venv python3-dev \
  libxml2-dev libxslt1-dev zlib1g-dev gcc \
  apache2 curl ca-certificates

mkdir -p "$APP_DIR"
cp -a "$SRC" "$APP_DIR/scraper4.py"
chmod 755 "$APP_DIR/scraper4.py"

"$PY" -m pip install --break-system-packages --upgrade pip
"$PY" -m pip install --break-system-packages \
  flask requests beautifulsoup4 lxml playwright cloudscraper curl_cffi gunicorn basalam-sdk
"$PY" -m playwright install --with-deps chromium || "$PY" -m playwright install chromium || true

install -m 644 "${REPO_DIR}/deploy/scraper4.service" /etc/systemd/system/scraper4.service
systemctl daemon-reload
systemctl enable --now scraper4.service

a2enmod proxy proxy_http headers rewrite >/dev/null
install -m 644 "${REPO_DIR}/deploy/scraper4.apache.conf" /etc/apache2/sites-available/scraper4.conf
a2dissite 000-default >/dev/null 2>&1 || true
a2ensite scraper4 >/dev/null || true
systemctl reload apache2

echo
echo "Scraper4 VPS is up."
echo "  health:  curl -sS http://127.0.0.1:8000/health"
echo "  public:  http://$(hostname -I | awk '{print $1}')/scraper4/"
systemctl --no-pager --full status scraper4 | head -20
