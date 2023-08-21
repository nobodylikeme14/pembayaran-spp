<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithProperties;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SiswaExport implements FromCollection, WithHeadings, WithStyles, WithProperties, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection() 
    {
        $data = Siswa::all()->map(function ($item, $index) {
            return [
                'No' => $index + 1,
                'NISN' => $item->nisn,
                'NIS' => $item->nis,
                'Nama' => $item->nama,
                'Kelas' => $item->kode_kelas,
                'Nomor HP' => $item->nomor_hp,
                'Alamat' => $item->alamat,
                'Email' => $item->email,
                'Username' => $item->username,
                'Password' => 'Siswa#' . $item->nisn
            ];
        });
        return $data;
    }

    public function headings(): array 
    {
        return [
            [
                'No',
                'NISN',
                'NIS',
                'Nama',
                'Kelas',
                'Nomor HP',
                'Alamat',
                'Email',
                'Username',
                'Password'
            ]
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function properties(): array
    {
        return [
            'creator'        => 'Your Name',
            'lastModifiedBy' => 'Your Name',
            'title'          => 'Exported Data',
            'description'    => 'This is an exported Excel file',
            'subject'        => 'Excel Export',
            'keywords'       => 'excel, export, laravel',
            'category'       => 'Data',
            'manager'        => 'Your Manager',
            'company'        => 'Your Company',
            'created'        => now(),
            'modified'       => now(),
            'protected'      => true, // Set protection to true
            'password'       => 'yourpassword', // Set a password
        ];
    }
}
