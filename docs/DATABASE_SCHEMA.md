## Overview

Skema database MVP, diimplementasikan di **PostgreSQL 17** (encoding UTF-8).
Semua tabel memakai primary key `bigint` dan `timestamps`.
Soft delete dipakai pada tabel yang menyimpan data inti.

Catatan implementasi:

- Hindari nama model atau tabel `Case` pada level kode karena berisiko bentrok dengan keyword PHP. Gunakan nama domain `AgrarianCase` dan tabel `agrarian_cases`.
- Laravel `$table->enum(...)` pada PostgreSQL diterjemahkan menjadi kolom `varchar` + `CHECK` constraint. Nilai di form UI **harus persis** sama dengan enum migrasi — mismatch akan memicu `SQLSTATE[23514]` check violation. Saat mengubah daftar status, selalu buat migrasi alter + sinkronkan opsi di Filament Resource/RelationManager.
- Untuk file upload, utamakan Spatie Media Library melalui media collection pada model. Jangan menambah foreign key ke tabel `media` tanpa alasan bisnis yang benar-benar kuat.
- Auto-generate kode bisnis (`member_code`, `case_code`, `program_code`) dilakukan di event `static::creating()` masing-masing model. Format standar: `<PREFIX>-YYYYMMDD-XXXXX` (5 karakter acak huruf besar).