# WAHA (Docker) untuk SEPETAK

[WAHA](https://github.com/devlikeapro/waha) adalah WhatsApp HTTP API yang dijalankan sendiri. Aplikasi Laravel memakai `X-Api-Key` dan endpoint seperti `POST /api/sendText` (lihat `config/waha.php`).

## Prasyarat

- [Docker Engine](https://docs.docker.com/engine/install/) dan plugin **Docker Compose v2**.

## Langkah cepat

1. Masuk ke direktori ini:

   ```bash
   cd ops/waha
   ```

2. **Inisialisasi kredensial** (menghasilkan `.env` berisi `WAHA_API_KEY`, password dashboard, dll.):

   ```bash
   chmod +x init-waha.sh
   ./init-waha.sh
   ```

   Bila Anda lebih suka salin template manual:

   ```bash
   cp .env.example .env
   # edit .env — ganti WAHA_API_KEY dan password dengan string panjang (mis. UUID)
   ```

3. **Jalankan kontainer:**

   ```bash
   docker compose up -d
   ```

4. Buka **Dashboard** untuk scan QR dan mengelola sesi (dari server: `curl` / browser via **SSH tunnel** `ssh -L 3000:127.0.0.1:3000 user@vps` lalu buka `http://127.0.0.1:3000/dashboard` di laptop Anda):

   - Secara default compose mem-bind **`127.0.0.1:3000`** saja agar dashboard tidak terpapar ke internet.
   - Untuk listen di semua interface: `WAHA_PUBLISH_HOST=0.0.0.0 docker compose up -d` (disarankan di belakang nginx + TLS).

5. Samakan kunci API dengan aplikasi SEPETAK (`.env` Laravel):

   ```env
   WAHA_ENABLED=true
   WAHA_BASE_URL=http://127.0.0.1:3000
   WAHA_API_KEY=<nilai WAHA_API_KEY dari ops/waha/.env>
   WAHA_SESSION=default
   ```

## Port dan jaringan

- **`WAHA_PUBLISH_HOST`** (default `127.0.0.1`) dan **`WAHA_PUBLISH_PORT`** (default `3000`) memetakan `host:container` di `docker-compose.yml`.
- Jika Laravel juga di Docker **jaringan yang sama**, gunakan hostname layanan `waha` dan URL basis `http://waha:3000` di `.env` Laravel (perlu menambahkan Laravel ke compose yang sama atau external network — dokumentasikan nanti bila stack disatukan).

## ARM (Apple Silicon, Raspberry Pi)

Menurut [quick start](https://waha.devlike.pro/docs/overview/quick-start/), unduh image `devlikeapro/waha:arm` lalu beri tag `devlikeapro/waha` sebelum `docker compose up`, atau ubah baris `image:` di `docker-compose.yml` menjadi `devlikeapro/waha:arm`.

## Cadangan

- Folder **`sessions/`** memuat data sesi WhatsApp; cadangkan bersama `.env` yang berisi kunci API.

## Produksi

Ikuti [Install & Update](https://waha.devlike.pro/docs/how-to/install/) dan [How to avoid blocking](https://waha.devlike.pro/docs/overview/%E2%9A%A0%EF%B8%8F-how-to-avoid-blocking/) — gunakan reverse proxy TLS, batasi rate pengiriman, dan patuhi kebijakan WhatsApp.
