# Audit & Perbaikan Paginasi

## Masalah yang Ditangani

- Paginasi terlihat “menghilang” pada halaman tertentu ketika pengguna berada di `?page=` yang sudah tidak valid (mis. setelah filter berubah atau konten berkurang). Kondisi ini membuat koleksi kosong dan UI masuk state “tidak ada data”, sehingga kontrol paginasi tidak terlihat.
- Implementasi paginasi tidak seragam antar halaman (query string seperti `theme`/filter tidak selalu terbawa ke link halaman berikutnya).
- Beranda memakai infinite scroll tanpa fallback HTML yang memadai saat JS gagal, sehingga pengguna bisa merasa paginasi tidak tersedia.

## Perbaikan

### 1) View Paginasi Seragam

- Menetapkan view paginasi default agar semua `->links()` memakai gaya yang konsisten:
  - [AppServiceProvider.php](file:///home/sepetak.org/app/Providers/AppServiceProvider.php)
  - [rev.blade.php](file:///home/sepetak.org/resources/views/pagination/rev.blade.php)
  - [simple-rev.blade.php](file:///home/sepetak.org/resources/views/pagination/simple-rev.blade.php)

### 2) Stabilitas: Redirect Ketika `page` Out-of-Range

- Jika hasil paginasi kosong tetapi `currentPage() > 1`, controller mengarahkan pengguna ke halaman terakhir yang valid. Ini mencegah kondisi “paginasi hilang” karena UI masuk empty state.
- Diterapkan pada index paginated: artikel (index, kategori, tag, penulis), kasus agraria, program advokasi, agenda, galeri.

### 3) Konsistensi Query String

- Menambahkan `withQueryString()` pada `paginate()` di seluruh controller yang relevan agar parameter seperti `theme` tetap terbawa saat pindah halaman.

### 4) Beranda: Progressive Enhancement

- Tab kategori di beranda sekarang berupa link biasa (server-side), dan JS hanya meng-upgrade menjadi fetch/infinite scroll.
- Paginasi HTML (`->links()`) ikut dirender sebagai fallback.

## Checklist QA Paginasi

- [ ] `/artikel`: paginasi terlihat dan bekerja pada desktop & mobile
- [ ] `/artikel?page=999`: redirect ke halaman terakhir yang valid (bukan empty state)
- [ ] `/artikel/kategori/{slug}`: paginasi tetap membawa query `theme` jika ada
- [ ] `/kasus-agraria`, `/program-advokasi`, `/agenda`, `/galeri`: paginasi konsisten secara visual dan fungsional
- [ ] Beranda: tab kategori bisa bekerja tanpa JS (link), dan paginasi fallback tersedia

## Uji Otomatis

- Ditambah regresi untuk redirect out-of-range pada halaman artikel: [PublicRoutesTest.php](file:///home/sepetak.org/tests/Feature/PublicRoutesTest.php)
