<?php

namespace App\Exports;

use App\Models\Member;
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

class MembersExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize, Responsable
{
    use Exportable;

    private string $fileName = 'anggota-sepetak.xlsx';

    public function __construct(
        private readonly ?string $status = null,
        private readonly ?string $gender = null,
    ) {
    }

    public function query(): Builder
    {
        return Member::query()
            ->with('address')
            ->when($this->status, fn (Builder $q) => $q->where('status', $this->status))
            ->when($this->gender, fn (Builder $q) => $q->where('gender', $this->gender))
            ->orderBy('member_code');
    }

    public function headings(): array
    {
        return [
            'Kode Anggota',
            'Nama Lengkap',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Telepon',
            'Email',
            'Status',
            'Tanggal Bergabung',
            'Alamat',
            'Desa',
            'Kecamatan',
            'Kabupaten',
            'Provinsi',
        ];
    }

    public function map($row): array
    {
        /** @var Member $row */
        $address = $row->address;

        return [
            $row->member_code,
            $row->full_name,
            match ($row->gender) {
                'male'   => 'Laki-laki',
                'female' => 'Perempuan',
                'other'  => 'Lainnya',
                default  => '-',
            },
            $row->birth_place ?: '-',
            optional($row->birth_date)->format('Y-m-d') ?: '-',
            $row->phone ?: '-',
            $row->email ?: '-',
            match ($row->status) {
                'pending'  => 'Pending',
                'active'   => 'Aktif',
                'inactive' => 'Tidak Aktif',
                'resigned' => 'Mengundurkan Diri',
                'deceased' => 'Meninggal',
                default    => $row->status,
            },
            optional($row->joined_at)->format('Y-m-d') ?: '-',
            $address->line_1 ?? '-',
            $address->village ?? '-',
            $address->district ?? '-',
            $address->regency ?? '-',
            $address->province ?? '-',
        ];
    }

    public function title(): string
    {
        return 'Anggota SEPETAK';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
