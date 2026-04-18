# Keputusan Teknologi

## Rekomendasi Utama

Laravel 11 + FilamentPHP 3 adalah kombinasi paling efisien untuk kebutuhan
SEPETAK, karena:

- Laravel stabil, dokumentasi lengkap, ekosistem luas
- FilamentPHP mempercepat pembuatan admin panel dan CRUD
- Livewire memudahkan interaktivitas tanpa JavaScript berat
- Mudah di-host pada VPS standar

## Alternatif yang Dipertimbangkan

- Django + Wagtail: kuat, tapi komunitas lokal lebih kecil
- Node.js + NestJS: fleksibel, tapi admin panel perlu custom
- WordPress + plugin: cepat, namun kurang fleksibel untuk data anggota dan kasus

## Trade-off

- Laravel butuh setup server PHP yang rapi
- FilamentPHP sangat bergantung pada model database yang rapi

## Paket Tambahan (Disarankan)

- Spatie Media Library untuk upload file
- Spatie Permission untuk role-based access
- Laravel Excel untuk export laporan
- Barryvdh/DomPDF untuk laporan PDF
- Redis untuk cache dan session

## Risiko dan Mitigasi

- Risiko: kebutuhan fitur berkembang cepat
  Mitigasi: MVP jelas dan roadmap bertahap
- Risiko: data anggota sensitif
  Mitigasi: role dan permission ketat
