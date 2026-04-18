@extends('exports.pdf._layout')

@section('title', 'Rekap Kasus Agraria SEPETAK')

@section('content')
    <h2>Rekap Kasus Agraria</h2>

    <table class="summary-grid">
        <tr>
            <td class="label">Total</td>
            <td>{{ number_format($summary['total']) }}</td>
            <td class="label">Dilaporkan</td>
            <td>{{ number_format($summary['reported']) }}</td>
            <td class="label">Ditinjau</td>
            <td>{{ number_format($summary['under_review']) }}</td>
            <td class="label">Mediasi</td>
            <td>{{ number_format($summary['mediation']) }}</td>
        </tr>
        <tr>
            <td class="label">Proses Hukum</td>
            <td>{{ number_format($summary['legal_process']) }}</td>
            <td class="label">Selesai</td>
            <td>{{ number_format($summary['resolved']) }}</td>
            <td class="label">Ditutup</td>
            <td>{{ number_format($summary['closed']) }}</td>
            <td class="label">Per</td>
            <td>{{ $generatedAt->translatedFormat('d F Y') }}</td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th style="width: 3%;">#</th>
                <th style="width: 12%;">Kode</th>
                <th style="width: 25%;">Judul</th>
                <th style="width: 15%;">Lokasi</th>
                <th style="width: 10%;">Mulai</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 9%;">Prioritas</th>
                <th style="width: 16%;">Penanggung Jawab</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cases as $i => $c)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $c->case_code }}</td>
                    <td>{{ $c->title }}</td>
                    <td>{{ $c->location_text ?: '-' }}</td>
                    <td>{{ optional($c->start_date)->format('Y-m-d') ?: '-' }}</td>
                    <td>
                        @php
                            $statusClass = match ($c->status) {
                                'reported'      => 'badge-info',
                                'under_review'  => 'badge-warning',
                                'mediation'     => 'badge-info',
                                'legal_process' => 'badge-danger',
                                'resolved'      => 'badge-success',
                                'closed'        => 'badge-gray',
                                default         => '',
                            };
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ $c->status }}</span>
                    </td>
                    <td>
                        @php
                            $priorityClass = match ($c->priority) {
                                'urgent' => 'badge-danger',
                                'high'   => 'badge-warning',
                                'medium' => 'badge-info',
                                'low'    => 'badge-gray',
                                default  => '',
                            };
                        @endphp
                        <span class="badge {{ $priorityClass }}">{{ $c->priority }}</span>
                    </td>
                    <td>{{ optional($c->leadUser)->name ?: '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align:center; color:#9ca3af;">Belum ada kasus agraria.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
