<?php

namespace App\Exports;

use App\Models\Event;
use App\Models\EventAttendance;
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

/**
 * Export flat daftar kehadiran (Event × Member).
 *
 * Jika `$event` disediakan, scope di-batasi ke kehadiran event tersebut.
 * Jika tidak, export seluruh record `event_attendance` (berguna untuk rekap
 * tahunan / audit departemen pendidikan & pelatihan kader).
 */
class EventAttendancesExport implements FromQuery, Responsable, ShouldAutoSize, WithHeadings, WithMapping, WithStyles, WithTitle
{
    use Exportable;

    private string $fileName = 'kehadiran-kegiatan-sepetak.xlsx';

    public function __construct(
        private readonly ?Event $event = null,
    ) {}

    public function query(): Builder
    {
        return EventAttendance::query()
            ->with(['event', 'member'])
            ->when($this->event, fn (Builder $q) => $q->where('event_id', $this->event->id))
            ->orderByDesc('event_id')
            ->orderBy('member_id');
    }

    public function headings(): array
    {
        return [
            'Event',
            'Tanggal Event',
            'Kode Anggota',
            'Nama Anggota',
            'Status Kehadiran',
            'Catatan',
        ];
    }

    public function map($row): array
    {
        /** @var EventAttendance $row */
        return [
            $row->event->title ?? '-',
            optional($row->event?->event_date)->format('Y-m-d H:i') ?: '-',
            $row->member->member_code ?? '-',
            $row->member->full_name ?? '-',
            match ($row->attendance_status) {
                'present' => 'Hadir',
                'absent' => 'Tidak Hadir',
                'excused' => 'Izin',
                default => $row->attendance_status ?: '-',
            },
            $row->notes ?: '-',
        ];
    }

    public function title(): string
    {
        return $this->event
            ? 'Kehadiran - '.\Illuminate\Support\Str::limit($this->event->title, 25)
            : 'Kehadiran Kegiatan';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
