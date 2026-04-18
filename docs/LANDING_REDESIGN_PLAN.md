# Landing Page Redesign — "Tani Merah"

Dokumen rekomendasi dan rencana pengembangan ulang tampilan halaman utama
`https://sepetak.org` dengan bahasa visual terinspirasi seni propaganda era
Soviet (Konstruktivisme 1920-an–30-an) dan Revolusi Kebudayaan Tiongkok
(1966–76), dikontekstualisasikan ke akar lokal perjuangan tani Indonesia
(seni rakyat 1950–65, mural-mural aktivis 1998, poster aksi KPA/SPI).

Dokumen ini bersifat **perencanaan** — tidak ada kode produksi yang diubah
oleh dokumen ini. Eksekusi dilakukan bertahap di fase implementasi.

---

## 1. Ringkasan Eksekutif

Landing page saat ini memakai bahasa visual LSM/korporat (gradasi hijau,
rounded-2xl, shadow soft, emoji ikon). Bahasa ini aman namun anonim dan
tidak membawa identitas perjuangan. Kami mengusulkan **identitas visual baru
"Tani Merah"** — kombinasi warna berani, tipografi stensil/kondensasi,
komposisi diagonal Konstruktivis, dan ikonografi alat-alat tani — yang
secara visual menegaskan posisi SEPETAK sebagai organisasi pekerja tani
yang militan namun terbuka.

Pedoman inti:

1. **Berani tanpa agresif** — warna kuat, tapi tidak mengagungkan
   kekerasan atau simbol partisan (palu-arit, bintang lima) yang terbebani
   sejarah politik Indonesia.
2. **Lokal di atas asing** — ambil kosakata visual Konstruktivisme/
   Revolusi Kebudayaan sebagai **bahasa formal** (komposisi, tipografi,
   warna), tapi isi narasi pakai simbol **lokal Karawang/Indonesia**
   (padi, cangkul, arit-sabit, perahu nelayan pesisir utara, poster LEKRA,
   mural Affandi/Dullah).
3. **Accessible by default** — warna merah jenuh tinggi bisa melelahkan
   mata dan menyulitkan disleksia/buta warna; kami tetapkan kontras,
   ritme whitespace, dan opsi `prefers-reduced-motion`.

---

## 2. Analisis Kata Kunci

Dari brief pengguna, kami ekstrak empat tema utama:

| Kata kunci pengguna | Tafsir visual konkret |
|--|--|
| "semangat era soviet" | Konstruktivisme Rusia 1920-an: diagonal, fotomontase, tipografi sans-serif tebal, grid terputus, palet merah+hitam+krem |
| "era china jaman revolusi kebudayaan" | Poster dazibao/xuanchuan hua: merah vermilion dominan, emas/ocher sekunder, ilustrasi realisme sosialis buruh-tani-tentara, komposisi sentral heroik, 仿宋/黑体 typography |
| "warna-warna berani" | Saturasi tinggi, kontras keras hitam-vs-merah-vs-krem, tidak ada gradasi lembut, bidang datar besar |
| "anti-kapitalisme" | Bahasa grafis yang **menolak** tropes korporat: bukan gradasi biru tech-bro, bukan rounded-xl neobrutal, bukan emoji. Ganti dengan: tegas sudut tajam, tipografi padat, simbol alat kerja (bukan gedung kaca atau grafik batang) |

### Apa yang **TIDAK** kami pakai (koridor etika)

- **Palu-arit, bintang lima, wajah Lenin/Stalin/Mao** — simbol partai
  komunis terbebani sejarah TAP MPRS XXV/1966. Memakainya dapat memicu
  kriminalisasi pengurus dan mengalihkan fokus dari substansi perjuangan
  agraria.
- **Tipografi Cyrillic atau karakter Tionghoa dekoratif** sebagai elemen
  estetis tanpa makna fungsional — dianggap cultural tokenism.
- **Gambar demonstrasi dengan kekerasan, api, atau senjata** — lawan
  prinsip "militan bukan militer".
- **Potret tokoh asing** (Che Guevara, Mao) — kami pakai wajah anonim
  petani Karawang (foto lapangan organisasi sendiri).

### Yang **DIPAKAI**

- **Merah bendera** (#C8102E) sebagai warna utama, bukan merah darah.
- **Diagonal 15°–22°** pada header/divider — ciri khas El Lissitzky.
- **Stensil & condensed sans-serif** untuk headline.
- **Fotomontase kolase** gaya Rodchenko: foto lapangan anggota diolah
  duotone merah+hitam dengan grain tinggi.
- **Nomor besar (five-year plan aesthetic)** untuk statistik anggota,
  kasus, luas lahan.
- **Ikon flat geometric**: padi, cangkul, sabit, perahu, rumah petani,
  matahari terbit (bukan emoji).

---

## 3. Referensi Visual & Historis

### 3.1 Konstruktivisme Rusia (1917–1932)

- **El Lissitzky** — "Beat the Whites with the Red Wedge" (1919):
  komposisi geometris, sudut tajam, red wedge memukul lingkaran putih.
- **Alexander Rodchenko** — desain poster Mayakovsky, sampul majalah
  *LEF*, fotomontase: crop ekstrem, sudut rendah, teks integrated ke foto.
- **Varvara Stepanova** — tekstil + desain panggung: pola geometris
  repetitif (inspirasi untuk section divider SEPETAK).
- **Gustav Klutsis** — slogan dalam stensil besar menutup foto pekerja.
- **Alexander Deineka** — lukisan kerja kolektif, komposisi horisontal
  heroik (→ inspirasi ilustrasi hero kita).

Kontribusi ke SEPETAK: grid diagonal, fotomontase duotone, stensil,
palet merah-hitam-krem.

### 3.2 Poster Revolusi Kebudayaan (1966–1976)

- Dominasi **大红 da hong** (merah pekat) + **金黄 jin huang** (kuning
  emas) + **纯白 chun bai** (putih).
- Komposisi: tokoh buruh/tani/tentara (工农兵) posisi sentral, mata
  mengarah ke masa depan, matahari terbit di belakang.
- Tipografi: **宋体** untuk slogan panjang, **黑体** untuk headline —
  padat, letter-spacing ketat, tidak pernah italic.

Kontribusi ke SEPETAK: saturasi warna, komposisi heroik sentral untuk
hero illustration, "matahari terbit" sebagai motif keberlanjutan.

**Catatan etika**: Revolusi Kebudayaan juga meliputi persekusi
intelektual, pembakaran buku, destruksi cagar budaya. Kami mengambil
**kosakata visualnya** bukan pembenaran ideologisnya. Dokumen internal
organisasi (About Us) tetap menegaskan posisi non-kekerasan, demokratis,
dan inklusif.

### 3.3 Akar Lokal Indonesia

- **LEKRA (Lembaga Kebudayaan Rakjat) 1950–1965** — seni rakyat, mural,
  poster realis sosialis Indonesia. Tokoh: Affandi, Hendra Gunawan,
  Dullah, S. Sudjojono.
- **Taring Padi (1998–sekarang)** — kolektif seni Yogyakarta, poster cukil
  kayu hitam-putih ber-aksen merah. **Referensi paling dekat** secara
  kultural dan paling aman secara politik.
- **Mural aksi agraria 2010-an** — dokumentasi aksi Teluk Jambe 2013,
  tol Jakarta–Cikampek 2013, BPN Karawang 2023 (sudah ada di konten
  SEPETAK).
- **Poster aksi KPA & SPI** — estetika kontemporer gerakan tani
  Indonesia, bahasa visual yang sudah dikenal kader.

Kontribusi: estetika cukil kayu (wood-cut) sebagai tekstur overlay untuk
foto, poster monokrom + merah sebagai visual bahasa native yang dikenali
anggota.

---

## 4. Prinsip Desain "Tani Merah"

Tujuh prinsip yang mengikat setiap keputusan visual:

1. **Merah bukan sekadar aksen — merah adalah bidang.** Tidak ada
   rounded soft. Blok warna datar besar.
2. **Sudut tegas.** `border-radius: 0`. Kalaupun ada radius, maksimum 2px
   untuk kartu kecil.
3. **Diagonal adalah ritme.** Setidaknya satu section per halaman
   memiliki pemotongan diagonal 15° atau 22°.
4. **Tipografi adalah peristiwa.** Headline pakai stensil kondensasi;
   tidak pernah dekoratif. Body pakai sans neutral tinggi keterbacaan.
5. **Fotomontase menggantikan stock photo.** Foto lapangan anggota
   di-duotone (merah #C8102E + hitam #0D0D0D), overlay grain, potong
   dengan clip-path angular.
6. **Nomor besar, label kecil.** Statistik: angka ukuran 120–180px,
   label uppercase 12–14px letter-spacing 0.2em.
7. **Accessible by default.** Kontras teks ≥ 4.5:1. Warna merah tidak
   dipakai sendiri untuk info kritis. Respek
   `prefers-reduced-motion` (hentikan banner scrolling).

---

## 5. Design Tokens

### 5.1 Palet Warna

Diajukan sebagai **Tailwind theme extension** (ganti palet `primary`
hijau, tambah `ink`, `paper`, `ochre`, `earth`, `flag`).

```js
// tailwind.config.js (ekstensi yang diusulkan)
colors: {
  flag: {   // merah propaganda — utama
    50:  '#FFF2F3',
    100: '#FFD9DC',
    200: '#FAA7AE',
    300: '#F0717B',
    400: '#E33D4D',
    500: '#C8102E',  // DEFAULT
    600: '#A50B25',
    700: '#7E0A1E',
    800: '#590815',
    900: '#36040C',
  },
  ink: {    // hitam cetakan
    900: '#0D0D0D',
    800: '#1A1A1A',
    700: '#2B2B2B',
  },
  paper: {  // krem kertas lama
    50:  '#FCF9F1',
    100: '#F4EEDB',
    200: '#E7DDB7',
  },
  ochre: {  // kuning emas aksen (jarang)
    500: '#D4A017',
    600: '#B0841B',
  },
  earth: { 500: '#6B4423' }, // tanah, opsional
}
```

Rasio kontras yang dijamin (WCAG AA ≥ 4.5:1 body, ≥ 3:1 UI large):

| Background | Foreground | Rasio | Keterangan |
|--|--|--|--|
| `flag-500` (#C8102E) | `paper-50` (#FCF9F1) | 6.0:1 | AA ok (body) |
| `ink-900` (#0D0D0D) | `paper-50` | 18.6:1 | AAA |
| `paper-50` | `ink-900` | 18.6:1 | AAA |
| `paper-50` | `flag-700` (#7E0A1E) | 9.8:1 | AAA |
| `flag-500` | `ochre-500` | 2.3:1 | **tidak** untuk teks; ok untuk border/pattern saja |

### 5.2 Tipografi

| Peran | Keluarga | Alternatif | Spec |
|--|--|--|--|
| Display (hero, section H1) | **"Big Shoulders Stencil Display"** (Google Fonts) | "Anton", "Bebas Neue" | 900 weight, letter-spacing -0.01em, uppercase |
| Sub-display (H2) | **"Archivo Black"** | "Oswald 700" | uppercase, letter-spacing 0.02em |
| Body | **"Work Sans"** 400/600 | "Inter", "Roboto" | line-height 1.65, paragraf maks 72 char |
| Kutipan/berita lead | **"Roboto Slab"** 500 italic | "Source Serif" | sebagai variasi ritme |
| Angka statistik | "Big Shoulders Inline Display" | "Anton" | 180px, tabular-nums |
| Kode/label meta | **"Space Mono"** | monospace default | uppercase 0.24em tracking |

Rasio skala (perfect fourth, 1.333):

```
12 · 16 · 21 · 28 · 37 · 50 · 66 · 88 · 117 · 156 · 208  (px)
```

### 5.3 Spacing & Grid

- **Grid**: 12 kolom, gutter 24px desktop, 16px mobile. Section padding
  vertikal: `py-20 md:py-28`.
- **Breakpoints**: ikuti Tailwind default (sm 640, md 768, lg 1024,
  xl 1280).
- **Max-width konten**: 1200px (`max-w-6xl`).

### 5.4 Sudut, Border, Shadow

- `border-radius`: 0 default; kartu kecil 2px.
- Border tebal mencolok: `border-4` atau `border-b-8` hitam untuk
  menegaskan bidang.
- **No soft shadow**. Jika perlu elevasi: `box-shadow: 8px 8px 0 #0D0D0D`
  (poster-style offset shadow) atau `box-shadow: inset 0 -8px 0 #C8102E`.

### 5.5 Motion

- Scroll reveal maksimum fade + translate 8px, durasi 200ms.
- Banner slogan scrolling (ticker) horizontal — dimatikan saat
  `prefers-reduced-motion: reduce`.
- Tidak ada hover scale di kartu (3D parallax dilarang — terlalu
  korporat/gimmicky).

---

## 6. Ikonografi & Imagery

### 6.1 Ikon custom (ganti semua emoji)

Buat satu set SVG flat 24×24 monokrom (stroke 2px, sudut tegas):

- `icon-sickle.svg` — sabit (tani)
- `icon-hoe.svg` — cangkul
- `icon-wheat.svg` — seikat padi
- `icon-boat.svg` — perahu nelayan pesisir utara Karawang
- `icon-house.svg` — rumah tani
- `icon-sun.svg` — matahari terbit bergaris (bukan bulat gradien)
- `icon-megaphone.svg` — kampanye
- `icon-scales.svg` — hukum/advokasi
- `icon-fist.svg` — solidaritas (hati-hati: pakai terbuka, bukan pegang
  senjata)
- `icon-signature.svg` — pendaftaran anggota

Alternatif library siap-pakai: **Tabler Icons** (outline, konsisten) atau
**Phosphor Regular** — keduanya MIT license. Di-override weight-nya jadi
2.5px untuk menyatu dengan bahasa "berani".

### 6.2 Fotomontase duotone

Filter CSS untuk semua foto anggota/aksi:

```css
.photo-duotone {
  filter: grayscale(100%) contrast(115%);
  mix-blend-mode: multiply;
  background: linear-gradient(180deg, #C8102E 0%, #36040C 100%);
}
```

Atau preprocess dengan **Spatie MediaLibrary conversion** untuk
menghasilkan versi `red-duotone.jpg` per foto (otomatis saat upload).
Versi duotone disajikan di publik, versi asli tetap di admin.

### 6.3 Tekstur

Grain noise subtle (opacity 8%) di seluruh hero section via SVG filter
inline:

```html
<svg class="absolute inset-0 w-full h-full opacity-[0.08]">
  <filter id="noiseFilter">
    <feTurbulence type="fractalNoise" baseFrequency="0.9" numOctaves="2"/>
  </filter>
  <rect width="100%" height="100%" filter="url(#noiseFilter)"/>
</svg>
```

Menambah kesan cetakan poster lama tanpa file raster tambahan.

---

## 7. Layout Patterns (Wireframe)

Berikut wireframe ASCII tiap section halaman utama. Tanda `▞` menandai
bidang warna merah, `■` hitam, `░` krem/paper.

### 7.1 Navigasi

```
░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
  [SEPETAK*]   TENTANG  PROGRAM  BERITA  KONTAK  [DAFTAR]
▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞
```

- Background paper-50, border-bottom merah 4px
- Logo SEPETAK di-set pakai "Big Shoulders Stencil" + asterisk sebagai
  pengganti bintang (≠ palu-arit)
- Tombol "DAFTAR" blok hitam solid dengan teks krem

### 7.2 Hero Section

```
▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞
▞                                                       ▞
▞   TANAH                      ┌─────────────┐         ▞
▞   UNTUK                      │  FOTOMONTASE │         ▞
▞   MEREKA                     │  DUOTONE    ■■         ▞
▞   YANG                       │  PETANI + ANAK        ▞
▞   MENGGARAPNYA.              │  DI SAWAH   │         ▞
▞                              │             │         ▞
▞   ─────                      │   [clip-path angular] ▞
▞   Serikat Pekerja Tani       │             │         ▞
▞   Karawang sejak 10-12-07    └─────────────┘         ▞
▞                                                       ▞
▞   [▒ DAFTAR ANGGOTA]  [ BACA BERITA ▸]               ▞
▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞
░░░░░░░░░░  [ticker: SLOGAN ◂◂ AKSI BULAN INI ◂◂ ...] ░░
```

- Hero background flag-500 (#C8102E) full-bleed
- Headline 3-baris: "TANAH / UNTUK MEREKA / YANG MENGGARAPNYA." —
  tipografi Big Shoulders Stencil, 88–117px, uppercase, warna paper-50
- Di bawah headline: rule horizontal 4px + tagline pendek
- Kolase foto di kanan, crop diagonal, duotone merah+hitam
- CTA ganda: tombol primer hitam solid + tombol ghost outline putih
- Di bawah hero: pita ticker krem berisi slogan aksi terkini (opsional,
  disable saat reduced-motion)

Alternatif versi yang mengurangi intensitas merah (untuk mobile /
kondisi cahaya rendah): background `paper-50` dominant, blok merah
diagonal hanya 40% lebar.

### 7.3 Stats (Pencapaian Organisasi)

```
░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
     RENCANA LIMA TAHUN — PENCAPAIAN
     ────────────────────────────────
     
     1.247        58          12
     ANGGOTA      KASUS       PROGRAM
     AKTIF        DITANGANI   ADVOKASI

     ▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞
```

- Latar paper-50
- Angka 156px, tabular-nums, warna flag-500
- Label uppercase tracking-wide, warna ink-900
- Pita merah pemisah di bawah

### 7.4 Berita Terbaru

```
░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
BERITA DARI LAPANGAN                        [LIHAT SEMUA ▸]
════════════════════════════════════════════════════════

┌───────────┐  ┌───────────┐  ┌───────────┐
│ ▞▞▞▞▞▞▞▞▞ │  │ ▞▞▞▞▞▞▞▞▞ │  │ ▞▞▞▞▞▞▞▞▞ │
│ duotone   │  │ duotone   │  │ duotone   │
│ photo     │  │ photo     │  │ photo     │
├───────────┤  ├───────────┤  ├───────────┤
│ 16 APR '26│  │ 14 APR '26│  │ 10 APR '26│
│           │  │           │  │           │
│ Judul     │  │ Judul     │  │ Judul     │
│ berita... │  │ berita... │  │ berita... │
│           │  │           │  │           │
│ BACA ▸    │  │ BACA ▸    │  │ BACA ▸    │
└═══════════┘  └═══════════┘  └═══════════┘
  (border 4px ink-900, no radius, box-shadow offset 6px 6px flag-500)
```

- Tiap kartu: border 4px hitam, no radius, poster-style offset shadow
  6px merah
- Gambar duotone setengah area kartu
- Tanggal monospace uppercase dengan tracking
- Judul "Archivo Black", 3 baris maks

### 7.5 Tentang Kami / Siapa SEPETAK

```
■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
■                                                       ■
■  SIAPA                     ┌─────────────────────────┐ ■
■  SEPETAK?                  │ ▢ Advokasi Hukum        │ ■
■                            │ ▢ Pemberdayaan Tani     │ ■
■  [paragraf sejarah singkat]│ ▢ Solidaritas           │ ■
■                            │ ▢ Kampanye Kebijakan    │ ■
■  [Selengkapnya ▸]          └─────────────────────────┘ ■
■                                                       ■
■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
```

- Background ink-900 (hampir hitam)
- Heading paper-50, body paper-100
- Grid 4 kartu pilar kerja — ikon SVG custom (cangkul, sabit, padi,
  megaphone), teks putih, border 2px flag-500 kiri

### 7.6 CTA & Footer

```
▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞

  KERJA TERORGANISIR MENGALAHKAN MODAL TERKONSENTRASI.

  [ GABUNG SEKARANG ]

▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞▞
■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
  SEPETAK*             NAVIGASI       KONTAK
  Jl. ...              ─ Tentang      ─ info@sepetak.org
  Karawang             ─ Program      ─ instagram
  ─────                ─ Berita       ─ twitter
  "Tanah untuk         ─ Daftar       
  penggarap."          
■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
```

- CTA section: full merah dengan slogan besar Big Shoulders
- Footer: full hitam, 4 kolom, teks paper-100, link paper-50 hover
  ke flag-400

---

## 8. Pemetaan Redesign (Current → Baru)

| Element | Saat ini | Redesign |
|--|--|--|
| Hero bg | Gradient `primary-800→600` (hijau) | Solid `flag-500` + grain noise |
| Hero headline | `text-4xl lg:text-6xl font-extrabold` Inter | Big Shoulders Stencil 88–117px uppercase |
| Hero foto | Tidak ada (kosong) | Fotomontase duotone clip-path diagonal |
| Tombol primer | `rounded-lg bg-white text-primary-700` | Sudut tegas `bg-ink-900 text-paper-50` offset shadow |
| Stats kartu | `rounded-2xl bg-primary-50/amber-50/blue-50` | Flat paper-50, angka 156px flag-500, label caps |
| Ikon pilar kerja | Emoji (⚖️🌾🤝📣) | SVG custom set (scales, wheat, fist, megaphone) |
| News card | `rounded-2xl shadow-sm` gradient placeholder | `border-4 ink-900` offset shadow 6px flag-500, duotone foto |
| About bg | `bg-primary-900` (hijau tua) | `bg-ink-900` dengan aksen flag-500 pada pilar |
| CTA final | `rounded-xl bg-primary-600 shadow-lg` | Full flag-500 section + tombol hitam solid |
| Footer | (perlu dicek) | Full ink-900, grid 4 kolom, bendera merah di atas |
| Font family | Inter 400/500/600/700/800 | Big Shoulders Stencil + Archivo Black + Work Sans + Space Mono |
| Palette Tailwind | `primary` = green | `flag` = red + `ink`, `paper`, `ochre`, `earth` |

---

## 9. Accessibility & Inclusivity

1. **Kontras**: semua pasangan teks/latar dijamin ≥ 4.5:1 (AA). Teks
   besar (≥ 24px bold) ≥ 3:1.
2. **Buta warna**: merah tidak dipakai sendiri untuk menyampaikan
   status. Setiap status berwarna disertai label teks atau ikon.
3. **Disleksia**: body font "Work Sans" mendekati rekomendasi disleksia
   (tinggi x-height, counter shape berbeda). Line-height 1.65, paragraf
   maks 72 char.
4. **Motion**: semua animasi dihentikan saat `prefers-reduced-motion:
   reduce`. Ticker slogan → diam.
5. **Keyboard**: focus ring 3px ochre-500 (kontras tinggi di atas
   merah & hitam).
6. **Screen reader**: semua SVG ikon punya `<title>` atau `aria-hidden`
   jika dekoratif. Hero foto dengan `alt` deskriptif.
7. **Ukuran font**: respect user zoom sampai 200% tanpa layout broken
   (gunakan rem, bukan px absolut di body).
8. **Bahasa**: semua headline + body dalam Bahasa Indonesia. Tidak ada
   slogan asing tanpa terjemahan.

---

## 10. Risiko & Mitigasi

| Risiko | Dampak | Mitigasi |
|--|--|--|
| Tafsir politis (dicap "komunis") | Tinggi — kriminalisasi pengurus | Hindari palu-arit/bintang 5/potret asing; gunakan simbol alat tani lokal; hindari kata "proletar", gunakan "pekerja tani" |
| Buta warna merah-hijau (8% pria) | Sedang — kehilangan info | Info kritis tidak bergantung pada warna; label + ikon + teks |
| Kelelahan mata dari merah jenuh penuh | Sedang — bounce rate naik | Dominance merah maksimum 40% viewport per halaman; gunakan paper-50 sebagai "jeda"; dark mode opsional (fase 2) |
| Tipografi stensil sulit dibaca body | Tinggi | Stensil **hanya** untuk headline ≥ 32px; body selalu Work Sans regular |
| Font loading lambat | Sedang — CLS | `font-display: swap`, preload 2 font utama, subset Latin |
| Layout diagonal breaks di mobile | Sedang | Diagonal hanya di lg+ breakpoint; mobile tetap grid lurus |
| Foto anggota privasi | Tinggi | Gunakan foto yang punya izin tertulis (form pendaftaran tambah consent checkbox); blur wajah anak-anak |
| Kerepotan content team mengelola asset duotone | Sedang | Otomasi via Spatie MediaLibrary conversion: upload foto normal, sistem hasilkan versi duotone untuk frontend |

---

## 11. Rencana Implementasi (Fase)

Total estimasi: **12–16 hari kerja** (2 sprint).

### Fase 0 — Persetujuan & Moodboard (1–2 hari)

**Deliverable**:
- Moodboard (boleh Figma/Miro/PDF) berisi 12–16 referensi visual
- Proposal 3 varian hero section (full merah, split diagonal, foto-dominant)
- Approval dari pengurus SEPETAK sebelum lanjut ke kode

**Lolos kriteria**: pengurus setuju pada 1 varian utama.

### Fase 1 — Design Tokens & Foundation (2 hari)

**Deliverable**:
- `tailwind.config.js` update: palet `flag/ink/paper/ochre/earth`, font stack, skala, border-radius override
- `resources/css/app.css`: font import Google Fonts (Big Shoulders Stencil, Archivo Black, Work Sans, Space Mono), utility tambahan (`.photo-duotone`, `.offset-shadow`, `.grain-overlay`)
- `resources/views/layouts/app.blade.php`: inject preload font + default body class

**Tests**:
- Visual regression screenshot halaman existing (harus tetap render, hanya warna berubah)
- `npm run build` sukses
- Lighthouse contrast check

### Fase 2 — Komponen Inti (3 hari)

**Deliverable**:
- `resources/views/components/revolutionary-*.blade.php`:
  - `btn.blade.php` — tombol solid/ghost angular
  - `card.blade.php` — kartu berita 4px border + offset shadow
  - `stat.blade.php` — statistik raksasa
  - `banner-ticker.blade.php` — pita slogan horizontal
  - `icon.blade.php` — wrapper SVG ikon set custom
- `resources/views/components/diagonal-section.blade.php` — section container dengan clip-path bawah
- Set 10 ikon SVG di `public/icons/*.svg` (atau via `heroicons-o` replacement)

**Tests**:
- Feature test baru `ComponentRenderTest` — render tiap komponen dengan props minimal
- Storybook/Styleguide halaman internal `/style-guide` (protected /admin, untuk maintainer)

### Fase 3 — Halaman Utama (3 hari)

**Deliverable**:
- `resources/views/home.blade.php` — ulang penuh pakai komponen fase 2
- Fotomontase hero: upload 3 foto lapangan via admin → convert ke duotone (Spatie conversion)
- Ticker slogan: feed 3–5 slogan dari `site_settings` atau hardcoded array

**Tests**:
- `PublicRoutesTest::test_home_page_loads` tetap hijau
- Visual review di 4 breakpoint (375/768/1024/1440px)
- Lighthouse: Performance ≥ 85, Accessibility ≥ 95, Best Practices ≥ 95, SEO ≥ 95

### Fase 4 — Halaman Turunan (2–3 hari)

**Deliverable**:
- `resources/views/posts/index.blade.php` — arsip berita pakai card v2
- `resources/views/posts/show.blade.php` — detail berita, sidebar meta, tipografi body pakai Work Sans + drop cap ochre
- `resources/views/pages/show.blade.php` — halaman statis (tentang, visi-misi, dll.) dengan hero merah + konten paper
- `resources/views/members/registration/create.blade.php` — form pendaftaran: layout diagonal split, preview card "KARTU ANGGOTA" style poster

**Tests**: `PublicRoutesTest` + Livewire form tests tetap hijau.

### Fase 5 — Konten & Asset (1–2 hari)

**Deliverable**:
- Sesi foto lapangan (minimum 12 foto: aksi, rapat, sawah, nelayan, perempuan tani, anak tani — komposisi heroik sudut rendah)
- Upload ke admin, generate duotone conversion
- 5 slogan banner ticker di site_settings: daftar draft yang disetujui pengurus
- Update OG image default + favicon dengan palet baru

### Fase 6 — QA, Polish, Release (2 hari)

**Deliverable**:
- Cross-browser: Chrome/Safari/Firefox/Edge + Chrome Android + Safari iOS
- Lighthouse 4 halaman utama (home, berita, about, daftar)
- Screen reader pass (NVDA + VoiceOver) — hero heading + form
- Changelog + dokumentasi di `docs/DESIGN_SYSTEM.md`
- Soft launch: deploy ke produksi + Umumkan ke milis pengurus
- Post-launch A/B check (opsional): bandingkan CTR "Daftar Anggota" selama 2 minggu (baseline bulan sebelumnya)

---

## 12. Tim & Ketergantungan

| Peran | Kebutuhan | Estimasi effort |
|--|--|--|
| Design (Figma) | 1 orang | 3 hari (fase 0) |
| Frontend Laravel/Tailwind | 1 orang | 10 hari (fase 1-4) |
| Fotografer/dokumentasi | 1 orang | 1 hari sesi + 1 hari kurasi |
| Copy editor (slogan, headline) | 1 orang pengurus | 2 hari review |
| QA | 1 orang | 1 hari (fase 6) |

**Ketergantungan eksternal**:

- Google Fonts harus accessible (opsional fallback: self-host di
  `/public/fonts/` untuk privacy + performance).
- Spatie MediaLibrary Image conversions butuh `imagick` PHP extension
  di server produksi — **cek di /etc/php**.
- Persetujuan dari pengurus SEPETAK pada varian hero sebelum kode
  dimulai (fase 0 gate).

---

## 13. Referensi & Inspirasi

### Buku / Artikel

- *The Total Work of Art in European Modernism* — David Roberts (2011)
- *Red Star Over Russia* — Tate Modern exhibition catalog (2017)
- *Chinese Propaganda Posters* — Taschen (2015)
- *Seni Rupa Indonesia di Era Ideologi* — Jim Supangkat (1997)
- Taring Padi, *Seni Membongkar Tirani* — KITLV Press (2018)

### Arsip online

- https://www.chineseposters.net — 7000+ poster Revolusi Kebudayaan
- https://www.rusavantgarde.com — Konstruktivisme Rusia
- https://taringpadi.com — kolektif Taring Padi
- https://www.kpa.or.id/publikasi/poster — poster KPA (Konsorsium Pembaruan Agraria)

### Font sumber

- Google Fonts: Big Shoulders Stencil Display, Archivo Black, Work Sans, Space Mono (semua SIL OFL, bebas dipakai)
- Commercial alternatif: *ITC Lubalin Graph*, *FF Bau* (jika budget tersedia)

### Ikon

- https://tabler-icons.io (MIT, 4500+ ikon stroke konsisten)
- https://phosphoricons.com (MIT, 6 weights)

---

## 14. Keputusan Final (LOCKED — 17 April 2026)

Pengurus SEPETAK telah menetapkan 5 keputusan kunci. Seluruh implementasi Fase 1–6 mengikuti pilihan berikut:

| # | Pertanyaan | **Keputusan** | Implikasi teknis |
|--|--|--|--|
| 1 | Intensitas merah | **SPLIT** 50/50 paper ↔ flag | Hero memakai grid 2 kolom dengan diagonal cut (desktop ≥ lg); mobile stack paper di atas + blok merah tipis di bawah |
| 2 | Motif utama | **LANDSCAPE** (sawah Karawang + pesisir utara) | Ilustrasi SVG inline: matahari terbit bergaris, bidang sawah horizontal, bukit Purwakarta di belakang, siluet petani caping + perahu nelayan kecil di foreground. Tidak pakai foto stock |
| 3 | Font display | **Anton** (safe condensed) | `font-display: swap`, fallback sans-serif; skala hero 88–132px |
| 4 | Slogan hero | **"PEKERJA TANI SOKO GURU PEMBEBASAN"** | Dipecah 4 baris sesuai ritme (PEKERJA / TANI / SOKO GURU / PEMBEBASAN), bold Anton uppercase; slogan ini fixed, tidak di-rotate |
| 5 | Bahasa ilustrasi | **Realisme sosialis** | Siluet figuratif dengan postur heroik (petani memanggul cangkul dengan sudut rendah), bukan cukil/linocut; rendering pakai stroke/fill flat SVG 2–3 warna (ink, flag, ochre) |

Keputusan ini mengikat stilistika seluruh halaman. Perubahan memerlukan approval ulang pengurus + revisi dokumen ini.

---

## 15. Lampiran

### A. Contoh CSS (sketsa — bukan kode final)

```css
/* hero section */
.hero-revolutionary {
  background: #C8102E;
  color: #FCF9F1;
  padding: 7rem 1.5rem;
  position: relative;
  overflow: hidden;
}

.hero-revolutionary::before {
  content: '';
  position: absolute; inset: 0;
  background: url("data:image/svg+xml,<svg ...turbulence.../>");
  opacity: 0.08;
  pointer-events: none;
}

.hero-headline {
  font-family: "Big Shoulders Stencil Display", sans-serif;
  font-weight: 900;
  font-size: clamp(3rem, 8vw, 7.3rem);
  line-height: 0.95;
  letter-spacing: -0.01em;
  text-transform: uppercase;
}

/* offset shadow poster style */
.card-poster {
  background: #FCF9F1;
  border: 4px solid #0D0D0D;
  box-shadow: 8px 8px 0 #C8102E;
  transition: transform 120ms, box-shadow 120ms;
}
.card-poster:hover {
  transform: translate(-2px, -2px);
  box-shadow: 10px 10px 0 #C8102E;
}
@media (prefers-reduced-motion: reduce) {
  .card-poster { transition: none; }
}
```

### B. Contoh slogan kandidat (untuk review editor)

1. "TANAH UNTUK MEREKA YANG MENGGARAPNYA."
2. "KERJA TERORGANISIR MENGALAHKAN MODAL TERKONSENTRASI."
3. "NELAYAN BUKAN TAMU DI LAUTNYA SENDIRI."
4. "SATU KARAWANG, SATU PERJUANGAN AGRARIA."
5. "MEMBANGUN SERIKAT, MEMBANGUN KUASA RAKYAT TANI."

Perlu review pengurus — beberapa kata ("kuasa rakyat") bisa ditafsirkan
politis. Pilih 2–3 yang paling aman + kuat.

### C. Checklist Approval Fase 0

- [ ] Moodboard ditunjukkan ke 3 pengurus (ketua, sekretaris, kepala departemen advokasi)
- [ ] Disetujui palet warna final
- [ ] Disetujui font pasangan final
- [ ] Disetujui 1 varian hero dari 3 yang diajukan
- [ ] Disetujui 3 slogan dari daftar di lampiran B
- [ ] Disepakati tidak memakai simbol palu-arit/bintang 5/potret asing
- [ ] Konfirmasi foto lapangan punya consent tertulis

---

## 16. Ekstensi — Admin Panel "Tani Merah" (Fase 7 · 16 April 2026)

Bahasa visual yang sama diperluas ke panel admin FilamentPHP dengan
penyesuaian agar tidak merusak ergonomi data entry.

### 16.1 Prinsip penyesuaian

| Situs publik | Admin panel |
|--------------|-------------|
| Poster agitatif, kontras tinggi, slogan besar | Clarity-first, hierarki Filament tetap terjaga |
| Grain overlay di atas hero | Grain **tidak** dipakai (mengurangi legibility tabel) |
| Anton untuk seluruh headline besar | Anton hanya untuk brand + page heading + stat value |
| Card dengan shadow poster tebal | Shadow tipis (`4 4 0 #0D0D0D1A`) agar tidak berat |
| Radius 0 | Radius 0–2px (kompromi agar input tidak terasa kaku) |
| Dark mode belum final | Dark mode inverted (paper → ink), aksen merah tetap |

### 16.2 File baru

- `resources/css/filament/admin/theme.css` — theme resmi Filament, berisi 10 bagian (brand, tipografi, topbar, sidebar, button, section/table, input, badge, widget, login shell).
- `resources/css/filament/admin/tailwind.config.js` — extend palet `flag/ink/paper/ochre` + font Anton/Work Sans/Space Mono.
- `app/Filament/Pages/Auth/Login.php` — override view + layout default Filament.
- `resources/views/filament/components/brand-logo.blade.php` — lencana poster 40 px + wordmark SEPETAK.
- `resources/views/filament/components/footer.blade.php` — footer minimalis dengan strip merah tipis.
- `resources/views/filament/components/layout/split.blade.php` — layout 40/60 (poster kiri, form kanan) untuk halaman auth.
- `resources/views/filament/pages/auth/login.blade.php` — konten form login yang mempertahankan seluruh Livewire action dari `Filament\Pages\Auth\Login`.

### 16.3 File yang dimodifikasi

- `app/Providers/Filament/AdminPanelProvider.php`
  - `brandName('SEPETAK')` + `brandLogo()` memakai logo raster.
  - `favicon()` sinkron dengan situs publik.
  - `colors([...])` — primary dipetakan ke palet `flag` (bukan Green default).
  - `font('Work Sans')` via `GoogleFontProvider`.
  - `viteTheme('resources/css/filament/admin/theme.css')`.
  - `sidebarCollapsibleOnDesktop()` untuk kenyamanan.
  - `renderHook(FOOTER, …)` menambahkan footer branded.
  - `login(SepetakLogin::class)` menyambungkan halaman login kustom.
- `vite.config.js` — input `resources/css/filament/admin/theme.css`.

### 16.4 Validasi

- `npm run build` — berhasil tanpa warning (asset `theme-*.css` 118 KB gz 17 KB).
- `phpunit --testsuite=Feature` — 46 tests / 170 assertions lulus.
- `GET /admin` → 302 ke `/admin/login` (perilaku default Filament).
- `GET /admin/login` → 200, menyertakan theme admin + markup split layout (`sepetak-login-shell`, `sepetak-login-poster`, `sepetak-login-form`, `sepetak-login-headline`).
- Logo termuat dari `/img/logo/logo-96.png` + `logo-128.png` (retina) dengan cache-bust `?v=2`.

### 16.5 Backlog panel admin

- [ ] Dark mode audit — verifikasi kontras 4.5:1 untuk badge warna solid.
- [ ] Brand logo varian monokrom untuk latar merah (saat ini logo rasterisasi warna).
- [x] Widget chart pakai palet brand (flag/ink/ochre) — Fase 8 (audit mendalam).
- [ ] Halaman error (403/404/500) Filament mengikuti poster split yang sama.
- [ ] Page "Edit Profile" user → sesuaikan form dengan poster framing.

### 16.6 Fase 8 · Audit mendalam (16 April 2026)

Audit menyeluruh terhadap 9 Resource + 3 Widget + layout Filament, hasil:

**BUG yang diperbaiki**

| # | Lokasi | Masalah | Perbaikan |
|---|--------|---------|-----------|
| 1 | `MemberResource` (badge status `deceased`), `PostResource` & `PageResource` (badge status `archived`) | `color('secondary')` bukan bucket warna valid di Filament 3 → berpotensi throw / fallback primary | Diganti ke `gray`/`warning` yang semantis jelas |
| 2 | `EventResource` — field `photos_upload` | `CreateEvent` & `EditEvent` tidak memproses upload ke media library; file fisiknya menumpuk di disk tanpa terhubung ke model, kolom `photos_count` selalu 0 | Tambahkan `afterCreate`/`afterSave` yang menyalin upload ke collection `photos` (pola sama dengan `AgrarianCase`) |
| 3 | `UserResource` | Tidak ada field role — user baru lahir tanpa role, sehingga `canAccessPanel()` menolak login secara diam-diam | Tambah `Select::make('roles')` multi dengan hidrasi dari relasi Spatie, plus `afterCreate`/`afterSave` yang `syncRoles()` |
| 4 | Chart widgets | Warna hex hardcoded default Tailwind (hijau/biru/oranye) tidak selaras palet | `MembersChartWidget` pakai `flag` + `ink`, `CasesChartWidget` pakai `flag` + `ochre` |

**Konsistensi yang diselaraskan**

- `AdminPanelProvider::navigationGroups([...])` mengurutkan sidebar secara eksplisit (*Keanggotaan → Advokasi → Kegiatan → Konten → Pengaturan*) agar tidak bergantung urutan discovery.
- `PostResource` & `PageResource`: tambah `Action::make('preview')` (ikon external-link) ke halaman publik, plus `BulkAction` `publish` & `archive` untuk alur editorial massal. Kolom judul di-`wrap()+limit(80)`, tanggal diformat lokal, `defaultSort` ke `published_at desc` / `updated_at desc`.
- `UserResource` mendapat kolom **Role** berbadge (`superadmin=danger`, `admin=primary`, `operator=info`, `viewer=gray`), kolom **Login Terakhir** pakai `since()` dengan placeholder "Belum pernah", plus filter Role.
- Row action export di `EventResource` & `AdvocacyProgramResource` diselaraskan: urutan `Edit → Export → Delete`, label tanpa sufiks `(xlsx)`.

**Theme polish**

- `fi-btn-color-{success,warning,info}` — radius 0 + border 2px varian outline agar tidak "bulat-lunak" berdiri sendiri.
- Dropdown, modal, pagination, fieldset, tabs, user-menu trigger, global search → semua radius 0 + border/shadow poster.
- Pagination item aktif pakai `flag-50 + border flag-500 + flag-700 text` (sama pola dengan sidebar aktif).
- Body background di-lock ke `paper-50`/`ink-900` agar tidak muncul garis putih saat user agent memaksa `bg-white`.
- `@media (prefers-reduced-motion: reduce)` menonaktifkan translate/scale primary button.

**Regression tests**

`tests/Feature/Filament/AdminPanelRefinementTest.php` (6 tes, 22 asersi) menjaga:
1. List Member merender untuk anggota berstatus `deceased`.
2. List Post & List Page merender untuk konten berstatus `archived`.
3. `CreateUser` + roles menyimpan role via `afterCreate`.
4. `EditUser` memakai `syncRoles` (bukan append).
5. Kolom Role di `ListUsers` render tanpa error.
6. `CreateEvent` tanpa upload foto tidak throw & `photos_count` tetap 0.

**Validasi Fase 8**

- `npm run build` — OK (theme 123 KB · gz 17.78 KB).
- `phpunit --testsuite=Feature` — 54 tests / 194 assertions lulus.
- `GET /admin/login`, `GET /`, `GET /berita` — HTTP 200 di produksi (`sepetak.org`).
- `grep 'secondary' app/Filament/Resources/*.php` — 0 hasil.

---

**Dokumen ini dapat direvisi.** Versi 3, 16 April 2026 — pasca-audit mendalam panel admin.
Kontak review: tim frontend + pengurus SEPETAK.
