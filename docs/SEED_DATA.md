# Seed Data - SEPETAK

Dokumen ini berisi data awal yang disarankan untuk seeding database.
Gunakan data dummy untuk contoh konten dan jangan memakai data pribadi nyata.

## Roles

- super_admin
- admin
- editor
- staff
- member

## Permissions (Suggested)

- users.manage
- roles.manage
- posts.manage
- pages.manage
- members.view
- members.manage
- agrarian_cases.view
- agrarian_cases.manage
- advocacy.view
- advocacy.manage
- events.manage
- reports.view
- settings.manage
- media.manage

## Default Admin User

- name: Super Admin
- email: admin@sepetak.org
- password: set via env or prompt
- force_password_change: true

## Status Seeds

- membership_status: pending, active, inactive, resigned, deceased
- case_status: reported, under_review, mediation, legal_process, resolved, closed
- advocacy_status: planned, active, paused, completed
- event_status: planned, done, canceled
- post_status: draft, published, archived

## Default Categories

- Berita
- Advokasi
- Kegiatan
- Pengumuman

## Default Tags (Optional)

- agraria
- kedaulatan-pangan
- reforma-agraria
- pendidikan-tani

## Default Pages

- Tentang
- Struktur Organisasi
- Kontak
- Kebijakan Privasi

## Default Menu

- Beranda
- Profil
- Artikel
- Advokasi
- Kasus
- Kegiatan
- Kontak

## Sample Content

- 3 placeholder posts with title and excerpt only
- 1 placeholder advocacy program
- 1 placeholder event

## Notes

- Update default admin credentials after first login
- Keep seed data minimal and safe
- Jangan commit password admin default ke repository
