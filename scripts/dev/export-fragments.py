#!/usr/bin/env python3
"""Export StrReplace fragments for files that could not be fully restored."""
import json, os, sys

TRANSCRIPT = os.path.expanduser(
    "~/.cursor/projects/home-sepetak-org/agent-transcripts/"
    "c000dcc7-cca2-4012-83de-c3dfeb72961a/"
    "c000dcc7-cca2-4012-83de-c3dfeb72961a.jsonl"
)
OUT_DIR = "/home/sepetak.org/docs/recovery/fragments"
os.makedirs(OUT_DIR, exist_ok=True)

# files classified as STUB (no <?php / no proper header)
targets = [
 "/home/sepetak.org/app/Filament/Resources/AdvocacyProgramResource.php",
 "/home/sepetak.org/app/Filament/Resources/AdvocacyProgramResource/RelationManagers/ActionsRelationManager.php",
 "/home/sepetak.org/app/Filament/Resources/AgrarianCaseResource.php",
 "/home/sepetak.org/app/Filament/Resources/AgrarianCaseResource/Pages/CreateAgrarianCase.php",
 "/home/sepetak.org/app/Filament/Resources/AgrarianCaseResource/Pages/EditAgrarianCase.php",
 "/home/sepetak.org/app/Filament/Resources/AgrarianCaseResource/RelationManagers/PartiesRelationManager.php",
 "/home/sepetak.org/app/Filament/Resources/AgrarianCaseResource/RelationManagers/UpdatesRelationManager.php",
 "/home/sepetak.org/app/Filament/Resources/ArticlePoolResource.php",
 "/home/sepetak.org/app/Filament/Resources/ArticleTopicResource.php",
 "/home/sepetak.org/app/Filament/Resources/EventResource.php",
 "/home/sepetak.org/app/Filament/Resources/MemberResource.php",
 "/home/sepetak.org/app/Observers/PageObserver.php",
 "/home/sepetak.org/app/Observers/PostObserver.php",
 "/home/sepetak.org/app/Services/ArticleGeneratorService.php",
 "/home/sepetak.org/app/Services/ResponseParser.php",
 "/home/sepetak.org/app/Services/TopicPicker.php",
 "/home/sepetak.org/config/article-generator.php",
 "/home/sepetak.org/config/purifier.php",
 "/home/sepetak.org/database/seeders/DatabaseSeeder.php",
 "/home/sepetak.org/resources/views/welcome.blade.php",
 "/home/sepetak.org/tests/Feature/ExampleTest.php",
]
target_set = set(targets)

per_file = {p: [] for p in targets}

with open(TRANSCRIPT) as f:
    for line in f:
        try:
            ev = json.loads(line)
        except Exception:
            continue
        stack = [ev]
        while stack:
            o = stack.pop()
            if isinstance(o, dict):
                if o.get("type") == "tool_use" and o.get("name") == "StrReplace":
                    inp = o.get("input") or {}
                    p = inp.get("path")
                    if p in target_set:
                        per_file[p].append((inp.get("old_string", ""), inp.get("new_string", "")))
                stack.extend(o.values())
            elif isinstance(o, list):
                stack.extend(o)

for p, ops in per_file.items():
    rel = p.replace("/home/sepetak.org/", "").replace("/", "__")
    out_path = os.path.join(OUT_DIR, rel + ".fragments.md")
    with open(out_path, "w", encoding="utf-8") as fh:
        fh.write(f"# StrReplace fragments for `{p}`\n\n")
        fh.write(f"Total edits captured in transcript: **{len(ops)}**\n\n")
        fh.write("> These fragments are the only surviving traces of edits applied by the previous agent.\n")
        fh.write("> The baseline file existed before the session but is now missing, so the final content cannot be fully reconstructed.\n")
        fh.write("> Use the `new_string` blocks below as guidance when manually rewriting the file.\n\n")
        for i, (old, new) in enumerate(ops, 1):
            fh.write(f"## Edit #{i}\n\n")
            fh.write("### old_string\n\n```\n")
            fh.write(old)
            fh.write("\n```\n\n")
            fh.write("### new_string\n\n```\n")
            fh.write(new)
            fh.write("\n```\n\n---\n\n")
    print("wrote", out_path, "ops=", len(ops))
