@extends('exports.pdf._layout')

@section('title', 'Rekap Anggota SEPETAK')

@section('content')
    <h2>Rekap Anggota</h2>

    <table class="summary-grid">
        <tr>
            <td class="label">Total Anggota</td>
            <td>{{ number_format($summary['total']) }}</td>
            <td class="label">Aktif</td>
            <td>{{ number_format($summary['active']) }}</td>
            <td class="label">Pending</td>
            <td>{{ number_format($summary['pending']) }}</td>
            <td class="label">Tidak Aktif</td>
            <td>{{ number_format($summary['inactive']) }}</td>
        </tr>
        <tr>
            <td class="label">Laki-laki</td>
            <td>{{ number_format($summary['male']) }}</td>
            <td class="label">Perempuan</td>
            <td>{{ number_format($summary['female']) }}</td>
            <td class="label" colspan="2">Periode</td>
            <td colspan="2">s/d {{ $generatedAt->translatedFormat('d F Y') }}</td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th style="width: 3%;">#</th>
                <th style="width: 13%;">Kode Anggota</th>
                <th style="width: 22%;">Nama</th>
                <th style="width: 10%;">Gender</th>
                <th style="width: 12%;">Telepon</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 12%;">Bergabung</th>
                <th style="width: 18%;">Alamat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($members as $i => $m)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $m->member_code }}</td>
                    <td>{{ $m->full_name }}</td>
                    <td>
                        @switch($m->gender)
                            @case('male')    Laki-laki    @break
                            @case('female')  Perempuan    @break
                            @case('other')   Lainnya      @break
                            @default         -
                        @endswitch
                    </td>
                    <td>{{ $m->phone ?: '-' }}</td>
                    <td>
                        @php
                            $statusClass = match ($m->status) {
                                'active'   => 'badge-success',
                                'pending'  => 'badge-warning',
                                'inactive' => 'badge-gray',
                                'resigned' => 'badge-danger',
                                'deceased' => 'badge-gray',
                                default    => '',
                            };
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ $m->status }}</span>
                    </td>
                    <td>{{ optional($m->joined_at)->format('Y-m-d') ?: '-' }}</td>
                    <td>
                        @php
                            $addr = collect([
                                optional($m->address)->village,
                                optional($m->address)->district,
                                optional($m->address)->regency,
                            ])->filter()->implode(', ');
                        @endphp
                        {{ $addr ?: '-' }}
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align:center; color:#9ca3af;">Belum ada data anggota.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
