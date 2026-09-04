#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""Run a shell/python command on PythonAnywhere via the API and fetch its output.

PythonAnywhere has no API endpoint that starts a console process headlessly
(POST /consoles/ creates the object but only opening the console in a browser
starts it). What the token DOES allow is this reliable 3-step flow:

  1. Files API    POST /files/path{path}   -> upload the script (multipart "content")
  2. Schedule API POST /schedule/          -> run `python3 ... > out.txt 2>&1`
  3. Files API    GET  /files/path{path}   -> poll out.txt, print the result

Then the temporary scheduled task is deleted (DELETE /schedule/{id}/).

Examples (run this anywhere: your PC, or the PA console with $API_TOKEN):

    # upload local.py and run it at the next minute, print the output
    python3 pa_run.py --user Fazilatma --script ./check.py

    # or just run an arbitrary command at the next minute
    python3 pa_run.py --user Fazilatma --cmd 'python3 /home/Fazilatma/check.py --json'

    # EU-hosted account
    python3 pa_run.py --user Fazilatma --host https://eu.pythonanywhere.com --cmd '...'

Notes:
- free plans schedule daily tasks only (interval "daily"); the task is
  temporary and deleted by this script after it has run once.
- output is written to a file on the account (default pa_run_out.txt), so
  nothing is lost even if you miss the poll.

Requires only the Python standard library.
"""
from __future__ import annotations

import argparse
import json
import os
import sys
import time
import urllib.error
import urllib.parse
import urllib.request
import uuid

DEFAULT_HOST = "https://www.pythonanywhere.com"
DEFAULT_REMOTE_SCRIPT = "/home/{user}/.pa_run_script.py"
DEFAULT_OUTPUT = "/home/{user}/.pa_run_out.txt"


def api_url(host: str, user: str, path: str) -> str:
    return f"{host}/api/v0/user/{urllib.parse.quote(user, safe='')}/{path}"


def call(host: str, user: str, token: str, method: str, path: str,
         body: bytes | None = None, headers: dict | None = None):
    req = urllib.request.Request(api_url(host, user, path), data=body, method=method)
    req.add_header("Authorization", "Token " + token)
    for k, v in (headers or {}).items():
        req.add_header(k, v)
    try:
        with urllib.request.urlopen(req, timeout=60) as resp:
            return resp.status, resp.headers, resp.read()
    except urllib.error.HTTPError as exc:
        try:
            return exc.code, dict(exc.headers or {}), exc.read()
        except OSError:
            return exc.code, {}, b""


def upload(host: str, user: str, token: str, path: str, content: bytes) -> None:
    boundary = "----paRun" + uuid.uuid4().hex
    head = (f"--{boundary}\r\n"
            'Content-Disposition: form-data; name="content"; filename="run"\r\n'
            "Content-Type: application/octet-stream\r\n\r\n").encode("utf-8")
    body = head + content + f"\r\n--{boundary}--\r\n".encode("utf-8")
    status, _h, resp = call(host, user, token, "POST",
                            f"files/path{urllib.parse.quote(path, safe='/')}",
                            body, {"Content-Type": f"multipart/form-data; boundary={boundary}"})
    if status not in (200, 201):
        raise RuntimeError(f"آپلود فایل ناموفق بود (HTTP {status}): {resp[:300].decode('utf-8', 'replace')}")


def read_file(host: str, user: str, token: str, path: str):
    status, _h, resp = call(host, user, token, "GET",
                            f"files/path{urllib.parse.quote(path, safe='/')}")
    if status == 200:
        return resp
    return None


def schedule(host: str, user: str, token: str, command: str, at: float) -> dict:
    t = time.localtime(at)
    payload = {"command": command, "enabled": True, "interval": "daily",
               "hour": t.tm_hour, "minute": t.tm_min, "description": "pa_run.py (temporary)"}
    body = json.dumps(payload).encode("utf-8")
    status, _h, resp = call(host, user, token, "POST", "schedule/", body,
                            {"Content-Type": "application/json"})
    data = json.loads(resp.decode("utf-8", "replace")) if resp else {}
    if status not in (200, 201) or not isinstance(data, dict) or "id" not in data:
        raise RuntimeError(
            f"ساخت تسک زمان‌بندی‌شده ناموفق بود (HTTP {status}): "
            f"{resp[:300].decode('utf-8', 'replace')} — "
            "برخی پلان‌ها فقط یک تسک روزانه اجازه می‌دهند؛ از تب Tasks بررسی کنید.")
    return data


def delete_task(host: str, user: str, token: str, task_id) -> None:
    call(host, user, token, "DELETE", f"schedule/{task_id}/")


def next_minute(now: float = None) -> float:
    now = time.time() if now is None else now
    return (int(now // 60) + 1) * 60 + 5   # ~5s after the next full minute


def main() -> int:
    p = argparse.ArgumentParser(description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    p.add_argument("--user", help="PythonAnywhere username")
    p.add_argument("--token", help="API token (or $PA_API_TOKEN / $API_TOKEN)")
    p.add_argument("--host", default=DEFAULT_HOST, help=f"API host (default {DEFAULT_HOST})")
    p.add_argument("--script", help="local python file to upload and run")
    p.add_argument("--cmd", help="custom command to run (overrides --script)")
    p.add_argument("--remote-script", help="remote path for the uploaded file")
    p.add_argument("--output", help="remote output file (default ~/.pa_run_out.txt)")
    p.add_argument("--wait", type=int, default=120, help="max seconds to poll for output (default 120)")
    p.add_argument("--poll", type=int, default=3, help="poll interval seconds (default 3)")
    p.add_argument("--keep-task", action="store_true", help="do not delete the scheduled task after it runs")
    p.add_argument("--keep-output", action="store_true", help="leave the output file on the account")
    args = p.parse_args()

    user = args.user or os.environ.get("PA_USER") or os.environ.get("USER") or ""
    token = args.token or os.environ.get("PA_API_TOKEN") or os.environ.get("API_TOKEN") or ""
    if not user or not token:
        print("خطا: --user و --token لازم است (یا متغیر محیطی PA_API_TOKEN/API_TOKEN)", file=sys.stderr)
        return 2

    remote_script = args.remote_script or DEFAULT_REMOTE_SCRIPT.format(user=user)
    out_file = args.output or DEFAULT_OUTPUT.format(user=user)

    if args.cmd:
        # wrap in a subshell so the whole command's output lands in the file
        command = f"( {args.cmd} ) > {out_file} 2>&1; echo \"EXIT=$?\" >> {out_file}"
    else:
        if not args.script:
            print("خطا: --script یا --cmd بدهید", file=sys.stderr)
            return 2
        script = open(args.script, "rb").read()
        print(f"==> آپلود {args.script} → {remote_script}")
        upload(host=args.host, user=user, token=token, path=remote_script, content=script)
        command = f"python3 {remote_script} > {out_file} 2>&1; echo \"EXIT=$?\" >> {out_file}"

    at = next_minute()
    when = time.strftime("%Y-%m-%d %H:%M", time.localtime(at))
    print(f"==> زمان‌بندی اجرا: {when} (هر پلان رایگان فقط تسک روزانه دارد)")
    task = schedule(host=args.host, user=user, token=token, command=command, at=at)
    task_id = task["id"]
    print(f"==> تسک ساخته شد: id={task_id}")

    deadline = time.time() + args.wait
    marker = b"EXIT="
    content = None
    try:
        while time.time() < deadline:
            remaining = int(deadline - time.time())
            content = read_file(host=args.host, user=user, token=token, path=out_file)
            if content is not None and marker in content:
                break
            print(f"    در انتظار اجرا... ({remaining}s باقی مانده)", flush=True)
            time.sleep(args.poll)
        if content is None:
            content = read_file(host=args.host, user=user, token=token, path=out_file)
        if content is None:
            print("⏱  هنوز خروجی‌ای تولید نشده؛ تسک حفظ شد. فایل را بعداً با این دستور بخوانید:")
            print(f"   GET {args.host}/api/v0/user/{user}/files/path{out_file}")
            return 1
        print("════════════ خروجی ════════════")
        print(content.decode("utf-8", "replace"))
        print("════════════════════════════════")
        if not args.keep_output:
            upload(host=args.host, user=user, token=token, path=out_file, content=b"")
    finally:
        if not args.keep_task:
            delete_task(host=args.host, user=user, token=token, task_id=task_id)
            print(f"==> تسک {task_id} حذف شد")
    return 0


if __name__ == "__main__":
    sys.exit(main())
