#!/usr/bin/env python3
"""Replay Write/StrReplace events from the prior agent transcript
to reconstruct missing files under /home/sepetak.org/.
"""
import json, os, sys

TRANSCRIPT = os.path.expanduser(
    "~/.cursor/projects/home-sepetak-org/agent-transcripts/"
    "c000dcc7-cca2-4012-83de-c3dfeb72961a/"
    "c000dcc7-cca2-4012-83de-c3dfeb72961a.jsonl"
)
ROOT = "/home/sepetak.org/"

# Collect ordered tool_use events (Write / StrReplace / Delete) that target files
events = []
with open(TRANSCRIPT) as f:
    for line in f:
        try:
            ev = json.loads(line)
        except Exception:
            continue
        def walk(o):
            if isinstance(o, dict):
                if o.get("type") == "tool_use" and o.get("name") in ("Write", "StrReplace", "Delete"):
                    events.append(o)
                for v in o.values():
                    walk(v)
            elif isinstance(o, list):
                for v in o:
                    walk(v)
        walk(ev)

print(f"Total mutation events: {len(events)}", file=sys.stderr)

# Replay in order to build virtual FS state (only for paths we touch)
vfs = {}
for ev in events:
    name = ev.get("name")
    inp = ev.get("input") or {}
    path = inp.get("path") or inp.get("file_path") or inp.get("filePath")
    if not path:
        continue
    if name == "Write":
        vfs[path] = inp.get("contents", "")
    elif name == "StrReplace":
        old = inp.get("old_string", "")
        new = inp.get("new_string", "")
        cur = vfs.get(path)
        if cur is None:
            # Try reading real disk to seed baseline
            try:
                with open(path, "r", encoding="utf-8") as fh:
                    cur = fh.read()
            except Exception:
                cur = None
        if cur is not None and old in cur:
            vfs[path] = cur.replace(old, new, 1)
        else:
            # Can't apply (baseline missing); stub as new if not yet present
            if cur is None:
                vfs[path] = new  # best effort
    elif name == "Delete":
        vfs[path] = None  # mark deleted

# Restore only files missing on disk and within /home/sepetak.org/
restored = []
skipped_existing = 0
no_content = []
for path, content in vfs.items():
    if not path.startswith(ROOT):
        continue
    if content is None:
        continue
    if os.path.exists(path):
        skipped_existing += 1
        continue
    os.makedirs(os.path.dirname(path), exist_ok=True)
    with open(path, "w", encoding="utf-8") as fh:
        fh.write(content)
    restored.append(path)

print(f"Restored: {len(restored)}")
print(f"Skipped (already existed): {skipped_existing}")
for p in sorted(restored):
    print("  +", p)
