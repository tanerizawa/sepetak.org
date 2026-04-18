#!/usr/bin/env python3
"""Quarantine PHP stub files that lost their <?php prefix so Laravel can boot.
The stubs are moved (preserving relative path) to docs/recovery/stubs/."""
import os, shutil

ROOT = "/home/sepetak.org/"
QUARANTINE = "/home/sepetak.org/docs/recovery/stubs"

stubs = [
 "app/Filament/Resources/AdvocacyProgramResource.php",
 "app/Filament/Resources/AdvocacyProgramResource/RelationManagers/ActionsRelationManager.php",
 "app/Filament/Resources/AgrarianCaseResource.php",
 "app/Filament/Resources/AgrarianCaseResource/Pages/CreateAgrarianCase.php",
 "app/Filament/Resources/AgrarianCaseResource/Pages/EditAgrarianCase.php",
 "app/Filament/Resources/AgrarianCaseResource/RelationManagers/PartiesRelationManager.php",
 "app/Filament/Resources/AgrarianCaseResource/RelationManagers/UpdatesRelationManager.php",
 "app/Filament/Resources/ArticlePoolResource.php",
 "app/Filament/Resources/ArticleTopicResource.php",
 "app/Filament/Resources/EventResource.php",
 "app/Filament/Resources/MemberResource.php",
 "app/Observers/PageObserver.php",
 "app/Observers/PostObserver.php",
 "app/Services/ArticleGeneratorService.php",
 "app/Services/ResponseParser.php",
 "app/Services/TopicPicker.php",
 "config/article-generator.php",
 "config/purifier.php",
 "database/seeders/DatabaseSeeder.php",
 "resources/views/welcome.blade.php",
 "tests/Feature/ExampleTest.php",
]
moved = []
for rel in stubs:
    src = os.path.join(ROOT, rel)
    dst = os.path.join(QUARANTINE, rel)
    if not os.path.exists(src):
        continue
    os.makedirs(os.path.dirname(dst), exist_ok=True)
    shutil.move(src, dst)
    moved.append(rel)

print(f"Quarantined {len(moved)} stub files to {QUARANTINE}")
for r in moved:
    print("  -", r)
