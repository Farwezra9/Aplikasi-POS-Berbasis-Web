<?php

namespace App\Controllers;

use App\Models\ProdukModel;
use App\Models\TransaksiModel;

class Dashboard extends BaseController
{
    public function index()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $produkModel = new ProdukModel();
        $transaksiModel = new TransaksiModel();

        // admin
        if (session()->get('role') === 'admin') {
            $jumlahProduk = $produkModel->countAllResults();

            // Total produk terjual (hanya transaksi keluar)
            $totalProdukTerjual = $transaksiModel
                ->where('jenis_transaksi', 'keluar')
                ->selectSum('jumlah')
                ->first()['jumlah'] ?? 0;

            // Total pendapatan (hanya transaksi keluar)
            $totalPendapatan = $transaksiModel
                ->where('jenis_transaksi', 'keluar')
                ->selectSum('total_harga')
                ->first()['total_harga'] ?? 0;

            // Riwayat transaksi
            $transaksi = $transaksiModel
                ->select('transaksi.*, produk.nama_produk')
                ->join('produk', 'produk.id = transaksi.produk_id')
                ->orderBy('transaksi.id', 'DESC')
                ->findAll();

            $data = [
                'jumlahProduk'       => $jumlahProduk,
                'totalProdukTerjual' => $totalProdukTerjual,
                'totalPendapatan'    => $totalPendapatan,
                'transaksi'          => $transaksi
            ];

            return view('dashboard/halaman_admin', $data);
        }

        // User
        $data = [
            'produk'     => $produkModel->findAll(),
            'transaksi'  => $transaksiModel
                                ->select('transaksi.*, produk.nama_produk')
                                ->join('produk', 'produk.id = transaksi.produk_id')
                                ->orderBy('transaksi.id', 'DESC')
                                ->findAll()
        ];

        return view('dashboard/halaman_user', $data);
    }
}
