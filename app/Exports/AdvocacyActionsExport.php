<?php

namespace App\Exports;

use App\Models\AdvocacyAction;
use App\Models\AdvocacyProgram;
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
 * Export detail aksi program advokasi (flat: program × action).
 *
 * Satu baris = satu `advocacy_actions` record, dengan metadata program agar
 * bisa langsung dipotong per program/periode di Excel.
 */
class AdvocacyActionsExport implements FromQuery, Responsable, ShouldAutoSize, WithHeadings, WithMapping, WithStyles, WithTitle
{
    use Exportable;

    private string $fileName = 'aksi-advokasi-sepetak.xlsx';

    public function __construct(
        private readonly ?AdvocacyProgram $program = null,
    ) {}

    public function query(): Builder
    {
        return AdvocacyAction::query()
            ->with(['program', 'createdBy'])
            ->when($this->program, fn (Builder $q) => $q->where('advocacy_program_id', $this->program->id))
            ->orderByDesc('action_date');
    }

    public function headings(): array
    {
        return [
            'Kode Program',
            'Judul Program',
            'Status Program',
            'Tanggal Aksi',
            'Tipe Aksi',
            'Catatan',
            'Hasil / Outcome',
            'Dicatat Oleh',
        ];
    }

    public function map($row): array
    {
        /** @var AdvocacyAction $row */
        $program = $row->program;

        return [
            $program->program_code ?? '-',
            $program->title ?? '-',
            $program->status ?? '-',
            optional($row->action_date)->format('Y-m-d') ?: '-',
            match ($row->action_type) {
                'meeting' => 'Rapat',
                'training' => 'Pelatihan',
                'campaign' => 'Kampanye',
                'field_visit' => 'Kunjungan Lapangan',
                'legal' => 'Proses Hukum',
                'other' => 'Lainnya',
                default => $row->action_type ?: '-',
            },
            $row->notes ?: '-',
            $row->outcome ?: '-',
            $row->createdBy->name ?? '-',
        ];
    }

    public function title(): string
    {
        return $this->program
            ? 'Aksi - '.\Illuminate\Support\Str::limit($this->program->title, 25)
            : 'Aksi Advokasi';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
