<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Spp;
use Carbon\Carbon;
use Throwable;
use Storage;

class LaporanController extends Controller
{
    public function laporan(Request $request) {
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'spp'       => 'required|exists:spp,kode_spp',
                'bulan_spp' => 'required',
                'kelas'     => 'required|exists:kelas,kode_kelas',
            ]);
            try {
                $dataLaporan = Siswa::with(['pembayaran' => function ($query) use ($request) {
                    $query->where('bulan_dibayar', $request->bulan_spp)
                    ->with('spp')->whereHas('spp', function ($sppQuery) use ($request) {
                        $sppQuery->where('kode_spp', $request->spp);
                    })->orderBy('tanggal_bayar');
                }])->where('kode_kelas', $request->kelas)->orderBy('nama', 'asc')->get();                
                if (count($dataLaporan) > 0) {
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
                    $this->fpdf->Cell(0, 7, 'Laporan Pembayaran SPP '. $request->bulan_spp. ' '. substr($request->spp, -4). ' Kelas '. $request->kelas ,0,1,'C');
                    $this->fpdf->Cell(10, 5, '', 0, 1);
        
                    $this->fpdf->SetFont('Times', 'B', 11);
                    $this->fpdf->Cell(14, 6, 'No', 1, 0, 'C');
                    $this->fpdf->Cell(80, 6, 'Nama Siswa', 1, 0, 'C');
                    $this->fpdf->Cell(55, 6, 'Tanggal Pembayaran', 1, 0, 'C');
                    $this->fpdf->Cell(40, 6, 'Status', 1, 1, 'C');
        
                    foreach($dataLaporan as $index => $result){
                        $this->fpdf->SetFont('Times', '', 11);
                        $this->fpdf->Cell(14, 6, $index + 1 , 1, 0, 'C');
                        $this->fpdf->Cell(80, 6, $result->nama, 1, 0, 'L');
                        $this->fpdf->Cell(55, 6, isset($result->pembayaran[0]) ? Carbon::parse($result->pembayaran[0]->tanggal_bayar)->locale('id')->isoFormat('DD MMMM Y') : "-", 1, 0, 'L');
                        $this->fpdf->SetFont('Times', 'B', 11);
                        if (isset($result->pembayaran[0])) {
                            $this->fpdf->SetTextColor(28, 200 , 138);
                            $this->fpdf->Cell(40, 6, "LUNAS", 1, 1, 'L');
                        } else {
                            $this->fpdf->SetTextColor(231, 74, 59);
                            $this->fpdf->Cell(40, 6, "BELUM LUNAS", 1, 1, 'L');
                        }
                        $this->fpdf->SetTextColor(0,0,0);
                    }
        
                    $pdfFileName = 'EduCashLog-LaporanSPP-'. $request->kelas .'.pdf';
                    $pdfContent = $this->fpdf->Output('S');
                    Storage::put('storage/export/'. $request->kelas .'/'.$pdfFileName, $pdfContent);
                    return response()->json([
                        'pdf_name' => $pdfFileName,
                        'pdf_url' => asset('storage/export/'. $request->kelas .'/'.$pdfFileName)
                    ]);
                } else {
                    return Response()->json([
                        'message' => 'Tidak ada laporan pembayaran untuk untuk dicetak'
                    ], 404);
                }
            } catch (Throwable $th) {
                return Response()->json([
                    'message' => 'Terjadi kesalahan saat mencetak laporan pembayaran'
                ], 500);
            }
        }
        $dataSpp = Spp::select('id', 'kode_spp', 'nominal')->get();
        $dataKelas = Kelas::select('kode_kelas')->get();
        return view('back.laporan', [
            'dataSpp' => $dataSpp,
            'dataKelas' => $dataKelas
        ]);
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