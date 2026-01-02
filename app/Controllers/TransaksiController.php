<?php
namespace App\Controllers;
use App\Models\ProdukModel;
use App\Models\TransaksiModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TransaksiController extends BaseController
{
    public function index()
    {
        $produkModel = new ProdukModel();
        $transaksiModel = new TransaksiModel();

        $data['produk'] = $produkModel->findAll();
        $data['transaksi'] = $transaksiModel
            ->select('transaksi.*, produk.nama_produk')
            ->join('produk', 'produk.id = transaksi.produk_id')
            ->orderBy('transaksi.created_at', 'DESC')
            ->findAll();

        return view('transaksi/index', $data);
    }

    public function saveUser()
    {
        $produkModel = new ProdukModel();
        $transaksiModel = new TransaksiModel();

        $ids = $this->request->getPost('id'); 
        $produk_ids = $this->request->getPost('produk_id');
        $jumlahs = $this->request->getPost('jumlah');

        if (!is_array($produk_ids)) {
            $produk_ids = [$produk_ids];
            $jumlahs = [$jumlahs];
            $ids = [$ids];
        }

        $responses = [];
        foreach ($produk_ids as $index => $produk_id) {
            $id = $ids[$index] ?? null;
            $jumlah = (int)($jumlahs[$index] ?? 0);

            $produkBaru = $produkModel->find($produk_id);
            if (!$produkBaru) {
                $responses[] = ['status' => 'error', 'message' => "Produk ID $produk_id tidak ditemukan"];
                continue;
            }

            $oldJumlah = 0;
            $oldProdukId = 0;

            if ($id) {
                $old = $transaksiModel->find($id);
                if ($old) {
                    $oldJumlah = $old['jumlah'];
                    $oldProdukId = $old['produk_id'];
                }
            }

            $stokFinal = $produkBaru['sisa_stok'] - $jumlah;
            if ($id && $oldProdukId == $produk_id) {
                $stokFinal = ($produkBaru['sisa_stok'] + $oldJumlah) - $jumlah;
            }

            if ($stokFinal < 0) {
                $responses[] = ['status' => 'error', 'message' => "Stok tidak cukup untuk produk {$produkBaru['nama_produk']}"];
                continue;
            }

            if ($id && $oldProdukId > 0) {
                $produkModel->update($oldProdukId, [
                    'sisa_stok' => $produkModel->find($oldProdukId)['sisa_stok'] + $oldJumlah
                ]);
            }

            $produkModel->update($produk_id, [
                'sisa_stok' => $produkModel->find($produk_id)['sisa_stok'] - $jumlah
            ]);

            $total_harga = $jumlah * $produkBaru['harga'];
            $data = [
                'produk_id'   => $produk_id,
                'jumlah'      => $jumlah,
                'jenis_transaksi' => 'keluar',
                'total_harga' => $total_harga
            ];

            if ($id) {
                $transaksiModel->update($id, $data);
                $responses[] = ['status' => 'updated', 'produk' => $produkBaru['nama_produk']];
            } else {
                $transaksiModel->insert($data);
                $responses[] = ['status' => 'saved', 'produk' => $produkBaru['nama_produk']];
            }
        }

        return $this->response->setJSON($responses);
    }

    public function saveAdmin()
{
    $produkModel = new ProdukModel();
    $transaksiModel = new TransaksiModel();

    $ids = $this->request->getPost('id'); 
    $produk_ids = $this->request->getPost('produk_id');
    $jumlahs = $this->request->getPost('jumlah');
    $jenis = $this->request->getPost('jenis_transaksi');

    if (!is_array($produk_ids)) {
        $produk_ids = [$produk_ids];
        $jumlahs = [$jumlahs];
        $ids = [$ids];
        $jenis = [$jenis];
    }

    $responses = [];
    foreach ($produk_ids as $index => $produk_id) {
        $id = $ids[$index] ?? null;
        $jumlah = (int)($jumlahs[$index] ?? 0);
        $jenis_transaksi = $jenis[$index] ?? 'keluar';

        $produk = $produkModel->find($produk_id);
        if (!$produk) {
            $responses[] = ['status' => 'error', 'message' => "Produk tidak ditemukan"];
            continue;
        }

        $oldJumlah = 0;
        $oldProdukId = 0;
        $oldJenis = '';

        if ($id) {
            $old = $transaksiModel->find($id);
            if ($old) {
                $oldJumlah = $old['jumlah'];
                $oldProdukId = $old['produk_id'];
                $oldJenis = $old['jenis_transaksi'];
            }
        }

        $stokBaru = $produk['stok'];
        $sisa_stokBaru = $produk['sisa_stok'];

        if ($id && $oldProdukId == $produk_id) {
            if ($oldJenis === 'masuk') {
                $stokBaru -= $oldJumlah;
                $sisa_stokBaru -= $oldJumlah;
            } else {
                $sisa_stokBaru += $oldJumlah;
            }
        }

        if ($jenis_transaksi === 'masuk') {
            $stokBaru += $jumlah;
            $sisa_stokBaru += $jumlah;
        } else { 
            $sisa_stokBaru -= $jumlah;
        }

        if ($sisa_stokBaru < 0) {
            $responses[] = ['status' => 'error', 'message' => "Sisa stok tidak cukup untuk produk {$produk['nama_produk']}"];
            continue;
        }

        $produkModel->update($produk_id, [
            'stok' => $stokBaru,
            'sisa_stok' => $sisa_stokBaru
        ]);

        $total_harga = $jumlah * $produk['harga'];
        $data = [
            'produk_id'       => $produk_id,
            'jumlah'          => $jumlah,
            'jenis_transaksi' => $jenis_transaksi,
            'total_harga'     => $total_harga
        ];

        if ($id) {
            $transaksiModel->update($id, $data);
            $responses[] = ['status' => 'updated', 'produk' => $produk['nama_produk']];
        } else {
            $transaksiModel->insert($data);
            $responses[] = ['status' => 'saved', 'produk' => $produk['nama_produk']];
        }
    }

    return $this->response->setJSON($responses);
}


    public function getTransaksi($id)
    {
        $transaksiModel = new TransaksiModel();
        return $this->response->setJSON($transaksiModel->find($id));
    }

    public function delete($id)
{
    $transaksiModel = new TransaksiModel();
    $produkModel = new ProdukModel();

    $t = $transaksiModel->find($id);
    if ($t) {
        $produk = $produkModel->find($t['produk_id']);
        if (!$produk) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Produk tidak ditemukan']);
        }
        if ($t['jenis_transaksi'] === 'masuk') {
            $produkModel->update($produk['id'], [
                'stok' => max(0, $produk['stok'] - $t['jumlah']),
                'sisa_stok' => max(0, $produk['sisa_stok'] - $t['jumlah'])
            ]);
        } else {
            $produkModel->update($produk['id'], [
                'sisa_stok' => $produk['sisa_stok'] + $t['jumlah']
            ]);
        }

        $transaksiModel->delete($id);
        return $this->response->setJSON(['status' => 'deleted']);
    }

    return $this->response->setJSON(['status' => 'error', 'message' => 'Transaksi tidak ditemukan']);
}
public function export()
{
    $transaksiModel = new TransaksiModel();

    $transaksi = $transaksiModel
        ->select('transaksi.*, produk.nama_produk')
        ->join('produk', 'produk.id = transaksi.produk_id')
        ->orderBy('transaksi.created_at', 'DESC')
        ->findAll();

    $masuk = [];
    $keluar = [];

    foreach ($transaksi as $t) {
        if ($t['jenis_transaksi'] === 'masuk') {
            $masuk[] = $t;
        } else {
            $keluar[] = $t;
        }
    }
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Laporan Transaksi');
    function buatTabel(&$sheet, $data, $startCol, $judul, $bgTotal)
    {
        $sheet->setCellValue($startCol . "1", $judul);
        $sheet->mergeCells($startCol . "1:" . chr(ord($startCol) + 5) . "1");
        $sheet->getStyle($startCol . "1:" . chr(ord($startCol) + 5) . "1")->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle($startCol . "1:" . chr(ord($startCol) + 5) . "1")->getAlignment()->setHorizontal('center');
        $sheet->getStyle($startCol . "1:" . chr(ord($startCol) + 5) . "1")->getFill()
              ->setFillType(Fill::FILL_SOLID)
              ->getStartColor()->setARGB('FFD9E1F2');

        $headers = ['No', 'Produk', 'Jumlah', 'Total Harga', 'Tanggal', 'Jenis'];
        $sheet->fromArray($headers, null, $startCol . "3");
        $sheet->getStyle($startCol . "3:" . chr(ord($startCol) + 5) . "3")->getFont()->setBold(true);
        $sheet->getStyle($startCol . "3:" . chr(ord($startCol) + 5) . "3")->getAlignment()->setHorizontal('center');
        $sheet->getStyle($startCol . "3:" . chr(ord($startCol) + 5) . "3")->getFill()
              ->setFillType(Fill::FILL_SOLID)
              ->getStartColor()->setARGB('FFCCE5FF');
        $row = 4;
        $total = 0;
        foreach ($data as $no => $t) {
            $sheet->setCellValue($startCol . $row, $no + 1);
            $sheet->setCellValue(chr(ord($startCol) + 1) . $row, $t['nama_produk']);
            $sheet->setCellValue(chr(ord($startCol) + 2) . $row, $t['jumlah']);
            $sheet->setCellValue(chr(ord($startCol) + 3) . $row, $t['total_harga']);
            $sheet->setCellValue(chr(ord($startCol) + 4) . $row, date('d-m-Y', strtotime($t['created_at'])));
            $sheet->setCellValue(chr(ord($startCol) + 5) . $row, $t['jenis_transaksi']);
            $total += $t['total_harga'];
            $row++;
        }
        $sheet->getStyle($startCol . "3:" . chr(ord($startCol) + 5) . ($row - 1))
              ->getBorders()->getAllBorders()
              ->setBorderStyle(Border::BORDER_THIN);
        $sheet->setCellValue(chr(ord($startCol) + 2) . $row, "TOTAL :");
        $sheet->setCellValue(chr(ord($startCol) + 3) . $row, $total);
        $sheet->getStyle(chr(ord($startCol) + 2) . $row . ":" . chr(ord($startCol) + 3) . $row)
              ->getFont()->setBold(true);
        $sheet->getStyle(chr(ord($startCol) + 2) . $row . ":" . chr(ord($startCol) + 3) . $row)
              ->getFill()
              ->setFillType(Fill::FILL_SOLID)
              ->getStartColor()->setARGB($bgTotal);
        $sheet->getStyle(chr(ord($startCol) + 2) . $row . ":" . chr(ord($startCol) + 3) . $row)
              ->getBorders()->getAllBorders()
              ->setBorderStyle(Border::BORDER_THIN);
    }
    buatTabel(
        $sheet,
        $masuk,
        'A',
        "TRANSAKSI MASUK",
        'FFFFC1C1'
    );

    buatTabel(
        $sheet,
        $keluar,
        'H',
        "TRANSAKSI KELUAR",
        'FFC6F5C1' 
    );

    foreach (range('A', 'M') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    $filename = 'laporan_transaksi_' . date('Ymd_His') . '.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit();
}



}
?>
