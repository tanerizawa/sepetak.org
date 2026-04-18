<?php

namespace App\Exports;

use App\Models\AgrarianCase;
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

class AgrarianCasesExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize, Responsable
{
    use Exportable;

    private string $fileName = 'kasus-agraria-sepetak.xlsx';

    public function __construct(
        private readonly ?string $status = null,
    ) {
    }

    public function query(): Builder
    {
        return AgrarianCase::query()
            ->with('leadUser')
            ->when($this->status, fn (Builder $q) => $q->where('status', $this->status))
            ->orderBy('case_code');
    }

    public function headings(): array
    {
        return [
            'Kode Kasus',
            'Judul',
            'Ringkasan',
            'Lokasi',
            'Tanggal Mulai',
            'Status',
            'Prioritas',
            'Penanggung Jawab',
            'Ditutup Pada',
        ];
    }

    public function map($row): array
    {
        /** @var AgrarianCase $row */
        return [
            $row->case_code,
            $row->title,
            $row->summary,
            $row->location_text ?: '-',
            optional($row->start_date)->format('Y-m-d') ?: '-',
            $row->status ?: '-',
            $row->priority ?: '-',
            $row->leadUser->name ?? '-',
            optional($row->closed_at)->format('Y-m-d') ?: '-',
        ];
    }

    public function title(): string
    {
        return 'Kasus Agraria';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
