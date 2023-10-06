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
        $username = strtolower(substr(str_replace(' ', '', $row[3]), 0, 5) . substr($row[1], -7));
        return new Siswa([
            'nisn' => $row[1],
            'nis' => $row[2],
            'nama' => $row[3],
            'kode_kelas' => $row[4],
            'nomor_hp' => $row[5],
            'alamat' => $row[6],
            'email' => $username."@educashlog.com",
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
