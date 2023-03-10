<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PdfController extends Controller
{
    protected $fpdf;

    public function __construct()
    {
        $this->fpdf = new Fpdf;
    }

    public function index(Request $request)
    {
        $id = $request->input('request_id');
        $req = \App\Models\Request::find($id);

        $username = $req->User->name;
        $binusianid = $req->User->binusianid;
        $phone = $req->User->phone;
        $division = $req->User->division->name;

        $purpose = $req->purpose;
        $lokasi = $req->lokasi;
        $isu = $req->return_notice;
        $book_date = date("d M Y H:i", strtotime($req->book_date));
        $return_date = date("d M Y H:i", strtotime($req->return_date));


        if ($req->status == 'on use'){
            $status = 'belum dikembalikan';
            $desc = '';
        }
        else{
            $status = $req->return_status;
            $desc = $req->return_notes;
        }

        $booking = Booking::firstWhere('request_id', $req->id);

        $taken_date = date("d M Y H:i", strtotime($booking->taken_date));
        if($req->status == 'on use'){
            $realize_return_date = '';
        }
        else{
            $realize_return_date = date("d M Y H:i", strtotime($booking->realize_return_date));
        }

        $this->fpdf->AddPage();

        $this->fpdf->SetFont('Arial', 'B', 16);
        $this->fpdf->Cell(190, 7, 'Formulir Peminjaman Peralatan', 0, 1, 'C');

        $this->fpdf->Ln();

        $titikdua = ': ';

        $nama = $titikdua . $username;
        $bid = $titikdua . $binusianid;
        $prodiv = $titikdua . $division;
        $hp = $titikdua . $phone;
        $keperluan = $titikdua . $purpose;
        $lok = $titikdua . $lokasi;
        $book = $titikdua . $book_date;
        $return = $return_date;

        $this->fpdf->SetFont('Arial', '', 11);
        $this->fpdf->Cell(50, 6, 'Nama Peminjam', 0, 0); $this->fpdf->Cell(15, 6, $nama, 0, 1);
        $this->fpdf->Cell(50, 6, 'Binusian ID', 0, 0); $this->fpdf->Cell(40, 6, $bid, 0, 0); $this->fpdf->Cell(25, 6, 'Prodi/Unit', 0, 0); $this->fpdf->Cell(15, 6, $prodiv, 0, 1);
        $this->fpdf->Cell(50, 6, 'No. Handphone', 0, 0); $this->fpdf->Cell(40, 6, $hp, 0, 1);
        $this->fpdf->Ln();
        $this->fpdf->Cell(50, 6, 'Keperluan', 0, 0); $this->fpdf->Cell(40, 6, $keperluan, 0, 1);
        $this->fpdf->Cell(50, 6, 'Peralatan dipakai di', 0, 0); $this->fpdf->Cell(40, 6, $lok, 0, 1);
        $this->fpdf->Cell(50, 6, 'Tanggal Peminjaman', 0, 0); $this->fpdf->Cell(40, 6, $book . ' - ' . $return, 0, 1);

        $this->fpdf->Ln();

        $this->fpdf->SetFont('Arial', 'B', 11);
        $this->fpdf->Cell(10, 6, 'No', 1, 0, 'C');
        $this->fpdf->Cell(30, 6, 'Nomor Seri', 1, 0, 'C');
        $this->fpdf->Cell(70, 6, 'Jenis', 1, 0, 'C');
        $this->fpdf->Cell(70, 6, 'Spesifikasi', 1, 1, 'C');

        $this->fpdf->SetFont('Arial', '', 11);

        //masi error
        $bookings = DB::table('bookings')
            ->join('assets', 'bookings.asset_id', '=', 'assets.id')
            ->join('asset_categories', 'bookings.asset_category_id', '=', 'asset_categories.id')
            ->select('assets.serial_number', 'assets.brand', 'asset_categories.name')
            ->where('bookings.request_id', '=', $id)
            ->get();

        $i = 1;
        foreach ($bookings as $b){
            $this->fpdf->Cell(10, 6, $i, 1, 0, 'C');
            $this->fpdf->Cell(30, 6, $b->serial_number, 1, 0, 'C');
            $this->fpdf->Cell(70, 6, $b->name, 1, 0, 'C');
            $this->fpdf->Cell(70, 6, $b->brand, 1, 1, 'C');
            $i++;
        }

        $this->fpdf->Ln();

        $this->fpdf->Cell(30, 6, 'Keterangan pengembalian', 0, 1);
        $this->fpdf->Cell(30, 6, 'Status Barang: ' . $status, 0, 1);
        $this->fpdf->Cell(180, 20, $desc, 1, 1);
        $this->fpdf->SetFont('Arial', 'B', 7);
        $this->fpdf->Cell(180, 6, 'Catatan: Peminjam (dan/atau anggota kelompoknya) bertanggung jawab dan bersedia menerima segala konsekuensi jika terjadi hal-hal yang tidak', 0, 1);
        $this->fpdf->Cell(180, 1, 'diinginkan terhadap peralatan yang dipinjam, serta bersedia menerima sanksi jika terlambat mengembalikan peralatan.', 0, 1);
        $this->fpdf->Cell(180, 8, '', 0, 1);

        $this->fpdf->Ln();

        $this->fpdf->SetFont('Arial', 'B', 7);
        $this->fpdf->Cell(110, 6, '', 0, 0);
        $this->fpdf->Cell(35, 6, 'Tanggal ambil', 1, 0, 'C');
        $this->fpdf->Cell(35, 6, 'Tanggal kembali', 1, 1, 'C');
        $this->fpdf->SetFont('Arial', '', 7);
        $this->fpdf->Cell(110, 6, '', 0, 0);
        $this->fpdf->Cell(35, 6, $taken_date, 1, 0, 'C');
        $this->fpdf->Cell(35, 6, $realize_return_date, 1, 1, 'C');

        $is_Approve = '';
        if($req->status == "done"){
            $is_Approve = 'APPROVED';
        }

        $this->fpdf->SetFont('Arial', 'B', 12);
        $this->fpdf->SetTextColor(0, 150, 0);
        $this->fpdf->Cell(110, 6, '', 0, 0);
        if($isu == 'isu_rusak') {
            $this->fpdf->SetTextColor(255, 0, 0);
        }
        $this->fpdf->Cell(70, 6, $is_Approve, 1, 1, 'C');
        $this->fpdf->SetFont('Arial', '', 7);
        $this->fpdf->SetTextColor(0, 0, 0);
        if($isu == 'isu_rusak'){
            $this->fpdf->SetTextColor(255, 0, 0);
            $this->fpdf->Cell(180, 6, 'Isu kerusakan barang akan dibahas lebih lanjut dengan BM [contact BM]', 0, 1, 'R');
        }
        $this->fpdf->SetTextColor(0, 0, 0);
        $this->fpdf->Cell(180, 6, '*Dokumen ini sah diketahui SCC koordinator dan peminjam meskipun tanpa tanda tangan', 0, 1, 'R');

        $this->fpdf->Output();

        exit;
    }
}
