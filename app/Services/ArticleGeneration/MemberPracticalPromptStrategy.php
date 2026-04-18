<?php

namespace App\Services\ArticleGeneration;

use App\Models\ArticleTopic;
use App\Services\ArticleGeneration\Contracts\PromptStrategyInterface;

/**
 * Prompt untuk artikel harian ringkas anggota — terpisah dari jalur akademik pillar.
 */
final class MemberPracticalPromptStrategy implements PromptStrategyInterface
{
    private const SYSTEM = <<<'PROMPT'
Kamu adalah penulis materi organisasi untuk Serikat Pekerja Tani Karawang (SEPETAK). Tugasmu menghasilkan artikel **ringan dan praktis** bagi anggota di desa (bukan esai panjang akademik).

═══════════════════════════════════════════
ATURAN
═══════════════════════════════════════════

▸ FORMAT: MARKDOWN bersih. Bahasa Indonesia baku, jelas, singkat. Tanpa emoji. Tanpa catatan meta tentang proses menulis.
▸ NADA: **santai namun tetap formal** — sopan, instruktif, solidaritas pekerja tani; hindari jargon teori yang tidak perlu. Pembaca utama adalah **petani/pekerja tani** yang masih membiasakan diri dengan teks terstruktur: gunakan kalimat yang mudah diikuti, definisi singkat bila perlu, tanpa gaya karya ilmiah penuh (tanpa abstrak atau pendahuluan panjang bergaya esai).
▸ PANJANG: **550–1.000 kata** (tidak termasuk daftar pustaka).
▸ SITASI: minimal **3** sitasi inline bergaya (Organisasi / Penulis, Tahun) tersebar; gunakan sumber nyata (UU, peraturan daerah, panduan KPA/BPN, buku teks ringan, situs resmi).
▸ DAFTAR PUSTAKA: minimal **2** entri nyata (APA ringkas boleh).

═══════════════════════════════════════════
STRUKTUR WAJIB
═══════════════════════════════════════════

1. **JUDUL** — `# Judul yang konkret dan membantu`

2. **METADATA** (setelah judul):
   ```
   **Kata Kunci:** a, b, c, d, e
   **Waktu Baca:** ± X menit
   ```

3. **DAFTAR ISI** — `## Daftar Isi` + daftar bernomor 4–6 poin (Pembuka ringkas, isi utama 2–4 bagian, penutup).

4. **RINGKASAN** — `## Ringkasan praktis` — 80–150 kata: siapa diuntungkan, apa yang dilakukan pembaca, batasan isi.

5. **ISI** — 2–4 sub-bagian `## ...` dengan contoh langkah, daftar cek, atau skenario singkat. Tiap sub-bagian boleh diakhiri blok:
   `> **Poin Penting:** ...`

6. **PENUTUP** — `## Penutup` — ajakan ringkas + rujukan ke struktur organisasi / advokasi SEPETAK bila relevan.

7. **DAFTAR PUSTAKA** — `## Daftar Pustaka` — minimal 2 referensi verifikabel.

═══════════════════════════════════════════
LARANGAN
═══════════════════════════════════════════

▸ Jangan mengarang undang-undang atau pasal fiktif — sebutkan hanya norma yang umum diketahui publik atau rujukan yang Anda cantumkan di pustaka.
▸ Jangan mengulang artikel sebelumnya: jika diberi daftar judul terbitan terbaru, pilih **sudut baru** (bukan parafrase judul yang sama).
PROMPT;

    public function systemPrompt(): string
    {
        return self::SYSTEM;
    }

    public function buildUserPrompt(ArticleTopic $topic, array $recentAutoPostTitles = []): string
    {
        $parts = [];

        $parts[] = '═══ INSTRUKSI PENULISAN — JALUR MATERI PRAKTIS ANGGOTA ═══';
        $parts[] = '**PENTING:** Ikuti **hanya** system prompt materi praktis di atas. Tulis seperti **panduan lapangan**: mudah dibaca petani yang masih beradaptasi dengan bahasa yang agak formal; **tanpa** abstrak, pendahuluan panjang bergaya karya ilmiah, atau pembahasan multi-bagian seperti esai panjang. Panjang, sitasi, dan struktur wajib mengikuti bagian ATURAN & STRUKTUR WAJIB di atas (550–1.000 kata, `## Ringkasan praktis`, pustaka minimal 2).';
        $parts[] = "Tulis sebuah **".PromptLexicon::humanizeArticleType($topic->article_type)."** dengan topik:\n\n> **{$topic->title}**";

        if ($topic->description) {
            $parts[] = "**Konteks dan sudut pandang:**\n{$topic->description}";
        }

        $parts[] = '**Kerangka pikir (untuk sudut argumentasi ringkas):** '.PromptLexicon::humanizeThinkingFramework($topic->thinking_framework);

        if (! empty($topic->key_references)) {
            $parts[] = $this->formatKeyReferences($topic);
        }

        if ($topic->prompt_template) {
            $parts[] = "**Instruksi tambahan:**\n{$topic->prompt_template}";
        }

        if ($recentAutoPostTitles !== []) {
            $maxListed = max(1, min(
                (int) (config('article-generator.member_practical_prompt.recent_title_max', 18) ?: 18),
                50,
            ));
            $bullets = collect($recentAutoPostTitles)
                ->take($maxListed)
                ->map(fn (string $t) => '- '.$t)
                ->implode("\n");
            $parts[] = "**Judul artikel otomatis yang baru-baru ini terbit (sampai {$maxListed} judul terbaru; hindari mengulang tema/judul yang sama; cari angle baru):**\n{$bullets}";
        }

        $parts[] = '═══ MULAI MENULIS ═══'."\n\n".'Tulis artikelnya sekarang. Mulai langsung dari judul (# Judul), lalu metadata, daftar isi, dan seterusnya sesuai struktur **praktis** yang ditentukan.';

        return implode("\n\n", $parts);
    }

    private function formatKeyReferences(ArticleTopic $topic): string
    {
        $refs = collect($topic->key_references)->map(function ($ref) {
            $author = $ref['author'] ?? 'Unknown';
            $year = $ref['year'] ?? '';
            $title = $ref['title'] ?? '';

            return "- {$author} ({$year}). *{$title}*.";
        })->implode("\n");

        return "**Referensi WAJIB** (disitasi ringkas di teks, boleh tambah sumber nyata relevan):\n{$refs}";
    }
}
