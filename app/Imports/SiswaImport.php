<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Hash;

class SiswaImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    private $importedRowCount = 0;

    public function model(array $row)
    {
        $this->importedRowCount++;
        $email_siswa = str_replace(' ', '', substr(strtolower($row[3]), -5).substr($row[1], -4).'@siswa.com');
        $username = substr($email_siswa, 0, -10);
        return new Siswa([
            'nisn' => $row[1],
            'nis' => $row[2],
            'nama' => $row[3],
            'kode_kelas' => $row[4],
            'nomor_hp' => $row[5],
            'alamat' => $row[6],
            'email' => $email_siswa,
            'username' => $username,
            'password' => Hash::make('Siswa#'.$row[1]),
            'privilege' => "Siswa"
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }

    public function getImportedRowCount()
    {
        return $this->importedRowCount;
    }
}
