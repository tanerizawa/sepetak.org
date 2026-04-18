<?php

namespace App\Exports;

use App\Models\Event;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EventsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize, Responsable
{
    use Exportable;

    private string $fileName = 'kegiatan-sepetak.xlsx';

    public function __construct(
        private readonly ?string $status = null,
    ) {
    }

    public function query(): Builder
    {
        return Event::query()
            ->withCount('attendances')
            ->with('organizer')
            ->when($this->status, fn (Builder $q) => $q->where('status', $this->status))
            ->orderByDesc('event_date');
    }

    public function headings(): array
    {
        return [
            'Judul',
            'Deskripsi',
            'Tanggal Acara',
            'Lokasi',
            'Status',
            'Penyelenggara',
            'Jumlah Kehadiran',
        ];
    }

    public function map($row): array
    {
        /** @var Event $row */
        return [
            $row->title,
            $row->description,
            optional($row->event_date)->format('Y-m-d H:i') ?: '-',
            $row->location_text ?: '-',
            $row->status ?: '-',
            $row->organizer->name ?? '-',
            (int) ($row->attendances_count ?? 0),
        ];
    }

    public function title(): string
    {
        return 'Kegiatan / Event';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
