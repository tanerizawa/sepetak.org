<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Laporan SEPETAK')</title>
    <style>
        * { font-family: DejaVu Sans, sans-serif; }
        body { font-size: 11px; color: #1f2937; margin: 0; padding: 24px; }
        h1 { font-size: 18px; margin: 0 0 4px; color: #15803d; }
        h2 { font-size: 13px; margin: 16px 0 6px; color: #14532d; border-bottom: 1px solid #d1d5db; padding-bottom: 2px; }
        .muted { color: #6b7280; font-size: 10px; }
        .header { border-bottom: 2px solid #15803d; padding-bottom: 10px; margin-bottom: 16px; }
        .summary-grid { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .summary-grid td { padding: 6px 10px; background: #f3f4f6; border: 1px solid #e5e7eb; }
        .summary-grid td.label { background: #ecfdf5; font-weight: bold; color: #065f46; }
        table.data { width: 100%; border-collapse: collapse; font-size: 10px; }
        table.data th { background: #15803d; color: #fff; text-align: left; padding: 6px 8px; border: 1px solid #166534; }
        table.data td { padding: 5px 8px; border: 1px solid #d1d5db; vertical-align: top; }
        table.data tr:nth-child(even) td { background: #f9fafb; }
        .badge { display: inline-block; padding: 1px 6px; border-radius: 10px; font-size: 9px; color: #fff; background: #64748b; }
        .badge-success { background: #16a34a; }
        .badge-warning { background: #ca8a04; }
        .badge-danger  { background: #dc2626; }
        .badge-info    { background: #0284c7; }
        .badge-gray    { background: #6b7280; }
        .footer { margin-top: 16px; font-size: 9px; color: #6b7280; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ \App\Models\SiteSetting::getValue('site_name', 'SEPETAK - Serikat Pekerja Tani Karawang') }}</h1>
        <div class="muted">
            {{ \App\Models\SiteSetting::getValue('site_tagline', 'Rebut Kedaulatan Agraria, Bangun Industrialisasi Pertanian') }}
            &mdash; {{ \App\Models\SiteSetting::getValue('contact_address', 'Kabupaten Karawang, Jawa Barat, Indonesia') }}
        </div>
    </div>

    @yield('content')

    <div class="footer">
        Dibuat pada {{ $generatedAt->translatedFormat('d F Y H:i') }} &middot;
        Dokumen internal SEPETAK &middot;
        {{ \App\Models\SiteSetting::getValue('contact_email', 'info@sepetak.org') }}
    </div>
</body>
</html>
