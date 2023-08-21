<?php

namespace App\Http\Controllers\System\Data;

use App\Http\Controllers\Controller;
use App\Http\Controllers\System\PusherController;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Imports\SiswaImport;
use App\Models\Siswa;
use App\Models\Kelas;
use DataTables;
use Storage;
use Session;
use Hash;
use DB;

class SiswaController extends Controller
{
    //FPDF
    private $fpdf;

    //Pusher
    private $pusherController;
    public function __construct(PusherController $pusherController)
    {
        $this->pusherController = $pusherController;
    }

    //Page
    public function siswa() {
        $datakelas = Kelas::all();
        return view('back.data.siswa', [
            'datakelas' => $datakelas
        ]);
    }

    //Data
    public function siswa_data() {
        $data = Siswa::select('id', 'nisn', 'nama', 'kode_kelas')->get();
        return DataTables::of($data)->make(true);
    }

    //Tambah
    public function siswa_tambah(Request $request) {
        $this->validate($request, [
            'nisn'      => 'required|max:20|unique:siswa,nisn',
            'nis'       => 'required|max:20|unique:siswa,nis',
            'nama'      => 'required|max:50',
            'kelas'     => 'required',
            'nomor_hp'  => 'required|max:20',
            'alamat'    => 'required',
        ],[
            'nisn.unique' => 'Data siswa dengan NISN ini sudah ada',
            'nis.unique' => 'Data siswa dengan NIS ini sudah ada'
        ]);
        $email_siswa = str_replace(' ', '', substr(strtolower($request->nama), -5).substr($request->nisn, -4).'@siswa.com');
        $username = substr($email_siswa, 0, -10);
        $siswa = new Siswa;
        $siswa->nisn = $request->nisn;
        $siswa->nis = $request->nis;
        $siswa->nama = $request->nama;
        $siswa->kode_kelas = $request->kelas;
        $siswa->nomor_hp = $request->nomor_hp;
        $siswa->alamat = $request->alamat;
        $siswa->email = $email_siswa;
        $siswa->username = $username;
        $siswa->privilege = "Siswa";
        $siswa->password = Hash::make('Siswa#'.$request->nisn);
        $siswa->save();
        $responses = [
            'status' => 'success', 
            'message' => 'Data siswa berhasil disimpan'
        ];
        if ($this->pusherController->isInternetConnected()) {
            $this->pusherController->triggerPusherEvent('dashboard-data', 'update-dashboard-data');
        }
        return Response()->json($responses); 
    }

    //Edit
    public function siswa_detail(Request $request) {
        $this->validate($request, [
            'id' => 'required|exists:siswa',
        ], [
            'id.required' => 'Mohon sertakan id siswa',
            'id.exists' => 'Id siswa tidak ditemukan'
        ]);
        $data = Siswa::where('id', $request->id)->get();
        $responses = [
            'status' => 'success', 
            'data' => $data
        ];
        return Response()->json($responses);
    }
    public function siswa_edit(Request $request) {
        $this->validate($request, [
            'nisn'      => 'required|max:20|unique:siswa,nisn,'.$request->id.',id',
            'nis'       => 'required|max:20|unique:siswa,nis,'.$request->id.',id',
            'nama'      => 'required|max:50',
            'kelas'     => 'required',
            'nomor_hp'  => 'required|max:20',
            'alamat'    => 'required',
        ]);
        $email_siswa = str_replace(' ', '', substr(strtolower($request->nama), -5).substr($request->nisn, -4).'@siswa.com');
        $username = substr($email_siswa, 0, -10);
        $siswa = Siswa::find($request->id);
        $siswa->nisn = $request->nisn;
        $siswa->nis = $request->nis;
        $siswa->nama = $request->nama;
        $siswa->kode_kelas = $request->kelas;
        $siswa->nomor_hp = $request->nomor_hp;
        $siswa->alamat = $request->alamat;
        $siswa->email = $email_siswa;
        $siswa->username = $username;
        $siswa->privilege = "Siswa";
        $siswa->password = Hash::make('Siswa#'.$request->nisn);
        $siswa->save();
        $responses = [
            'status' => 'success', 
            'message' => 'Data siswa berhasil diperbarui'
        ];
        if ($this->pusherController->isInternetConnected()) {
            $this->pusherController->triggerPusherEvent('dashboard-data', 'update-dashboard-data');
        }
        return Response()->json($responses); 
    }

    //Hapus
    public function siswa_hapus(Request $request) {
        $this->validate($request, [
            'id' => 'required|exists:siswa',
        ], [
            'id.required' => 'Mohon sertakan id siswa',
            'id.exists' => 'Id siswa tidak ditemukan'
        ]);
        Siswa::where('id', $request->id)->delete();
        $responses = [
            'status' => 'success', 
            'message' => 'Data siswa berhasil dihapus'
        ];
        if ($this->pusherController->isInternetConnected()) {
            $this->pusherController->triggerPusherEvent('dashboard-data', 'update-dashboard-data');
        }
        return Response()->json($responses);
    }

    //Export
    public function siswa_export(Request $request) {
        $this->validate($request, [
            'kelas' => 'required|exists:kelas,kode_kelas',
        ]);
        $dataSiswa = Siswa::where('kode_kelas', $request->kelas)
        ->orderBy('nama', 'asc')->get()->map(function ($item, $index) {
            return [
                'No' => $index + 1,
                'NISN' => $item->nisn,
                'Nama Siswa' => $item->nama,
                'Username' => $item->username,
                'Password' => 'Siswa#' . $item->nisn
            ];
        });
        if(count($dataSiswa) > 0){
            $this->fpdf = new PDF;
            $this->fpdf->AliasNbPages();
            $this->fpdf->AddPage();

            $imageUrl = public_path('assets/img/logo.png');
            $this->fpdf->Image($imageUrl, 20, 10, 28);
            $this->fpdf->Cell(30);
            $this->fpdf->SetFont('Times', 'B', 18);
            $this->fpdf->Cell(0, 8, config('app.name'), 0, 1, 'C');
            $this->fpdf->SetFont('Times', '', 13);
            $this->fpdf->Cell(30);
            $this->fpdf->Cell(0, 7, 'Ruko Edelwis Permai Blok AB3 No. 4',0 ,1 , 'C');
            $this->fpdf->Cell(30);
            $this->fpdf->Cell(0, 7, 'Jl. Raya Wangi Teh Melati Km. 3,8 Bandung 670977', 0, 1, 'C');
            $this->fpdf->Cell(30);
            $this->fpdf->Cell(0, 7, 'Telp. + 01 234 567 89 Fax. + 01 234 567 89 Mobile. + 01 234 567 89', 0, 1, 'C');
            $this->fpdf->Line(10, 43, 199, 43);
            $this->fpdf->Ln(7);

            $this->fpdf->Cell(5);
            $this->fpdf->SetFont('Times', 'B', 15);
            $this->fpdf->Cell(0, 7, 'Data Username dan Password Akun Siswa Kelas '. $request->kelas ,0,1,'C');
            $this->fpdf->Cell(10, 5, '', 0, 1);

            $this->fpdf->SetFont('Times', '', 12);
            $this->fpdf->Cell(0, 7, 'Gunakan Username/Email dan Password untuk login kedalam akun '.config('app.name').'.', 0, 1, 'L');

            $this->fpdf->SetFont('Times', 'B', 12);
            $this->fpdf->Cell(14, 6, 'No', 1, 0, 'C');
            $this->fpdf->Cell(30, 6, 'NISN', 1, 0, 'C');
            $this->fpdf->Cell(65, 6, 'Nama Siswa', 1, 0, 'C');
            $this->fpdf->Cell(30, 6, 'Username', 1, 0, 'C');
            $this->fpdf->Cell(50, 6, 'Password', 1, 1, 'C');
            
            foreach($dataSiswa as $result){
                $this->fpdf->SetFont('Times', '', 12);
                $this->fpdf->Cell(14, 6, $result['No'] , 1, 0, 'C');
                $this->fpdf->Cell(30, 6, $result['NISN'], 1, 0, 'L');
                $this->fpdf->Cell(65, 6, $result['Nama Siswa'], 1, 0, 'L');
                $this->fpdf->Cell(30, 6, $result['Username'], 1, 0, 'L');
                $this->fpdf->Cell(50, 6, $result['Password'], 1, 1, 'L');
            }

            $pdfFileName = 'EduCashLog-AkunSiswa-'. $request->kelas .'-Export.pdf';
            $pdfContent = $this->fpdf->Output('S');
            Storage::put('storage/export/'. $request->kelas .'/'.$pdfFileName, $pdfContent);
            return response()->json([
                'pdf_name' => $pdfFileName,
                'pdf_url' => asset('storage/export/'. $request->kelas .'/'.$pdfFileName)
            ]);
        } else {
            return Response()->json([
                'message' => 'Tidak ada data siswa untuk diexport'
            ], 404);
        }
    }

    //Import
    public function siswa_import(Request $request) {
        $this->validate($request, [
            'file_import' => 'required|file|mimes:csv,xls,xlsx'
        ]);
        DB::beginTransaction();
        try {
            set_time_limit(0);
            $file = $request->file('file_import');
            $filename = rand() . $file->getClientOriginalName();
            $file->move(public_path('storage/import'), $filename);
            $import = new SiswaImport();
            Excel::import($import, public_path('storage/import/' . $filename));
            $totalData = $import->getImportedRowCount();
            Storage::deleteDirectory('storage/import');
            DB::commit();
            $responses = [
                'status' => 'success', 
                'message' => $totalData. ' data siswa berhasil diimport'
            ];
            if ($this->pusherController->isInternetConnected()) {
                $this->pusherController->triggerPusherEvent('dashboard-data', 'update-dashboard-data');
            }
            return Response()->json($responses);
        } catch (\Exception $e) {
            DB::rollback();
            Storage::deleteDirectory('storage/import');
            return abort(500, $e->getMessage());
        } 
    }

    //Delete all
    public function siswa_hapus_all() {
        if(Siswa::count() > 0){
            Siswa::query()->delete();
            $responses = [
                'status' => 'success', 
                'message' => 'Semua data siswa berhasil dihapus'
            ];
            if ($this->pusherController->isInternetConnected()) {
                $this->pusherController->triggerPusherEvent('dashboard-data', 'update-dashboard-data');
            }
            return Response()->json($responses);
        } else {
            return Response()->json([
                'message' => 'Tidak ada data siswa untuk dihapus'
            ], 404);
        }
    }
}

class PDF extends FPDF{
    public function Footer(){
      $this->SetY(-15);
      $this->SetFont('Times','', 10);
      $this->Cell(1,10,'Copyright '.config('app.name').' '.date('Y'),0,0,'L');
      $this->Cell(0,10, $this->PageNo().' / {nb}',0,1,'R');
    }
}