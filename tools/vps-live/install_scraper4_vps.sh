#!/usr/bin/env bash
# Install Scraper4 VPS edition on this server.
# Apache keeps PHP on / ; Python UI is http://SERVER/put/
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
  flask requests beautifulsoup4 lxml playwright cloudscraper curl_cffi \
  gunicorn basalam-sdk httpx selenium playwright-stealth
# Full Chromium (not PythonAnywhere headless-shell).
"$PY" -m playwright install --with-deps chromium || "$PY" -m playwright install chromium || true

install -m 644 "${REPO_DIR}/deploy/scraper4.service" /etc/systemd/system/scraper4.service
systemctl daemon-reload
systemctl enable --now scraper4.service
systemctl restart scraper4.service

a2enmod proxy proxy_http headers rewrite >/dev/null

# Undo any previous deploy that stole Apache /
a2dissite scraper4 >/dev/null 2>&1 || true
rm -f /etc/apache2/sites-enabled/scraper4.conf
a2ensite 000-default >/dev/null 2>&1 || true

install -m 644 "${REPO_DIR}/deploy/scraper4.apache.conf" /etc/apache2/conf-available/scraper4-put.conf
# Do not a2enconf: ProxyPass must live inside the PHP vhost, not twice.
"$PY" - <<'PY'
from pathlib import Path
snippet = Path("/etc/apache2/conf-available/scraper4-put.conf").read_text(encoding="utf-8")
vhost = Path("/etc/apache2/sites-available/000-default.conf")
if not vhost.exists():
    vhost.write_text(
        "<VirtualHost *:80>\n"
        "    ServerAdmin webmaster@localhost\n"
        "    DocumentRoot /var/www/html\n"
        "    ErrorLog ${APACHE_LOG_DIR}/error.log\n"
        "    CustomLog ${APACHE_LOG_DIR}/access.log combined\n"
        "</VirtualHost>\n",
        encoding="utf-8",
    )
text = vhost.read_text(encoding="utf-8")
begin, end = "# scraper4-put BEGIN", "# scraper4-put END"
if begin in text:
    pre, rest = text.split(begin, 1)
    rest = rest.split(end, 1)[-1]
    text = pre.rstrip() + "\n" + snippet + rest.lstrip("\n")
elif "</VirtualHost>" in text:
    text = text.replace("</VirtualHost>", snippet + "\n</VirtualHost>", 1)
else:
    text += "\n" + snippet + "\n"
vhost.write_text(text, encoding="utf-8")
print("Apache /put/ injected into 000-default (PHP root kept).")
PY

apache2ctl configtest
systemctl reload apache2

echo
echo "Scraper4 VPS is up. PHP stays on / ; Python is /put/"
echo "  health:  curl -sS http://127.0.0.1:8000/health"
echo "  public:  http://$(hostname -I | awk '{print $1}')/put/"
systemctl --no-pager --full status scraper4 | head -20
