<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kartu Anggota — {{ $member->full_name }}</title>
    <link rel="icon" href="{{ asset('img/favicon.png') }}">
    <style>
        :root {
            --flag: #C8102E;
            --ink: #0D0D0D;
            --paper: #FCF9F1;
            --paper-100: #F4EFE2;
        }
        * { box-sizing: border-box; }
        html, body {
            margin: 0;
            padding: 0;
            font-family: "Work Sans", system-ui, sans-serif;
            background: #e5e5e5;
            color: var(--ink);
        }
        .page {
            min-height: 100vh;
            padding: 2rem 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
        }
        .toolbar {
            display: flex;
            gap: 0.5rem;
            font-family: "Space Mono", monospace;
            font-size: 0.8rem;
        }
        .toolbar button, .toolbar a {
            background: var(--ink);
            color: var(--paper);
            padding: 0.55rem 1rem;
            border: 3px solid var(--ink);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
        }
        .toolbar button:hover, .toolbar a:hover { background: var(--flag); border-color: var(--flag); }

        .card-wrap { display: flex; flex-direction: column; gap: 1rem; }
        .card {
            width: 340px;
            height: 215px;
            background: var(--paper);
            border: 4px solid var(--ink);
            position: relative;
            overflow: hidden;
            box-shadow: 6px 6px 0 var(--ink);
            font-family: "Work Sans", sans-serif;
        }
        .card.back { background: var(--paper-100); }
        .card .stripe {
            position: absolute; top: 0; left: 0; right: 0; height: 32px;
            background: var(--flag);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 12px;
            color: var(--paper);
            font-family: "Anton", sans-serif;
            font-size: 0.95rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
        }
        .card .stripe .org { font-size: 0.7rem; letter-spacing: 0.2em; font-family: "Space Mono", monospace; }
        .card .content {
            position: absolute; top: 38px; bottom: 8px; left: 12px; right: 12px;
            display: grid;
            grid-template-columns: 82px 1fr;
            gap: 10px;
        }
        .card .photo {
            width: 82px; height: 110px;
            background: #ddd center/cover no-repeat;
            border: 2px solid var(--ink);
        }
        .card .info { font-size: 0.72rem; line-height: 1.3; }
        .card .info .name {
            font-family: "Anton", sans-serif;
            font-size: 1.05rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            line-height: 1;
            margin-bottom: 4px;
        }
        .card .info .row { display: grid; grid-template-columns: 58px 1fr; gap: 4px; margin-top: 2px; }
        .card .info .row .k { font-family: "Space Mono", monospace; font-size: 0.58rem; text-transform: uppercase; color: #555; }
        .card .info .row .v { font-weight: 600; font-size: 0.68rem; word-break: break-word; }
        .card .code {
            position: absolute; bottom: 6px; left: 12px; right: 12px;
            display: flex; justify-content: space-between; align-items: center;
            font-family: "Space Mono", monospace; font-size: 0.6rem;
            color: #444;
        }
        .card .code .badge { background: var(--ink); color: var(--paper); padding: 2px 6px; letter-spacing: 0.1em; }

        .card.back .back-content {
            padding: 40px 14px 10px;
            font-family: "Space Mono", monospace;
            font-size: 0.6rem;
            line-height: 1.4;
            color: #333;
        }
        .card.back .back-content h3 {
            font-family: "Anton", sans-serif;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.15em;
            margin: 0 0 6px;
            color: var(--ink);
        }
        .card.back .signature {
            position: absolute; bottom: 10px; right: 14px; left: 14px;
            display: flex; justify-content: space-between; align-items: flex-end;
            font-size: 0.55rem;
        }
        .card.back .qr {
            width: 58px; height: 58px;
            background: repeating-conic-gradient(var(--ink) 0 25%, transparent 0 50%) 0 0 / 8px 8px;
            border: 2px solid var(--ink);
        }

        @media print {
            body { background: white; }
            .page { padding: 0; }
            .toolbar { display: none !important; }
            .card-wrap { flex-direction: row; gap: 8px; }
            .card { box-shadow: none; }
        }
    </style>
</head>
<body>
<div class="page">
    <div class="toolbar">
        <button type="button" onclick="window.print()">Cetak Kartu</button>
        <a href="{{ url('/admin/members') }}">Kembali</a>
    </div>

    <div class="card-wrap">
        {{-- Depan --}}
        <div class="card">
            <div class="stripe">
                <span>Kartu Tanda Anggota</span>
                <span class="org">SEPETAK</span>
            </div>
            <div class="content">
                @php($photo = $member->getFirstMediaUrl('photo'))
                <div class="photo" @if($photo) style="background-image:url('{{ $photo }}')" @endif></div>
                <div class="info">
                    <div class="name">{{ \Illuminate\Support\Str::limit($member->full_name, 28, '') }}</div>
                    <div class="row"><span class="k">Kode</span><span class="v">{{ $member->member_code }}</span></div>
                    <div class="row"><span class="k">Gender</span><span class="v">{{ ['male'=>'Laki-laki','female'=>'Perempuan','other'=>'Lainnya'][$member->gender] ?? '-' }}</span></div>
                    <div class="row"><span class="k">TTL</span><span class="v">{{ $member->birth_place ?: '-' }}{{ $member->birth_date ? ', '.$member->birth_date->format('d/m/Y') : '' }}</span></div>
                    <div class="row"><span class="k">Bergabung</span><span class="v">{{ optional($member->joined_at)->format('d/m/Y') ?: '-' }}</span></div>
                    <div class="row"><span class="k">Wilayah</span><span class="v">{{ $member->address?->regency ?: 'Karawang' }}</span></div>
                </div>
            </div>
            <div class="code">
                <span>ID · {{ str_pad($member->id, 5, '0', STR_PAD_LEFT) }}</span>
                <span class="badge">ANGGOTA RESMI</span>
            </div>
        </div>

        {{-- Belakang --}}
        <div class="card back">
            <div class="stripe">
                <span>Kartu Tanda Anggota</span>
                <span class="org">SEPETAK.ORG</span>
            </div>
            <div class="back-content">
                <h3>Serikat Pekerja Tani Karawang</h3>
                Kartu ini adalah tanda keanggotaan resmi. Kehilangan wajib dilaporkan ke Sekretariat. Data dilindungi sesuai ketentuan internal organisasi. Verifikasi keabsahan kartu dapat dilakukan melalui Sekretariat di sepetak.org/kontak.
            </div>
            <div class="signature">
                <div>
                    <div style="font-family:'Anton',sans-serif;font-size:0.9rem;letter-spacing:0.12em;text-transform:uppercase;color:var(--ink)">Sekretariat</div>
                    <div>{{ \App\Models\SiteSetting::getValue('site_name', 'SEPETAK') }}</div>
                </div>
                <div class="qr" aria-hidden="true"></div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
