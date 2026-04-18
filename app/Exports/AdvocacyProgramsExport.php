<?php

namespace App\Exports;

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

class AdvocacyProgramsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize, Responsable
{
    use Exportable;

    private string $fileName = 'program-advokasi-sepetak.xlsx';

    public function __construct(
        private readonly ?string $status = null,
    ) {
    }

    public function query(): Builder
    {
        return AdvocacyProgram::query()
            ->with('leadUser')
            ->when($this->status, fn (Builder $q) => $q->where('status', $this->status))
            ->orderBy('program_code');
    }

    public function headings(): array
    {
        return [
            'Kode Program',
            'Judul',
            'Deskripsi',
            'Lokasi',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Status',
            'Penanggung Jawab',
        ];
    }

    public function map($row): array
    {
        /** @var AdvocacyProgram $row */
        return [
            $row->program_code,
            $row->title,
            $row->description,
            $row->location_text ?: '-',
            optional($row->start_date)->format('Y-m-d') ?: '-',
            optional($row->end_date)->format('Y-m-d') ?: '-',
            $row->status ?: '-',
            $row->leadUser->name ?? '-',
        ];
    }

    public function title(): string
    {
        return 'Program Advokasi';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
