<?php
namespace App\Controllers;
use App\Models\ProdukModel;

class ProdukController extends BaseController
{
    public function index()
    {
        $model = new ProdukModel();
        $data['produk'] = $model->findAll();
        return view('produk/index', $data);
    }

    public function save()
    {
        $model = new ProdukModel();
        $id = $this->request->getPost('id');

        $fileGambar = $this->request->getFile('gambar');
        $gambarName = null;

        if($fileGambar && $fileGambar->isValid() && !$fileGambar->hasMoved()){
            $gambarName = $fileGambar->getRandomName();
            $fileGambar->move(FCPATH . 'uploads', $gambarName);
        }
        if ($id) {
            $produk = $model->find($id);
            $data = [
                'nama_produk' => $this->request->getPost('nama_produk'),
                'harga' => $this->request->getPost('harga'),
                'total_harga' => $this->request->getPost('harga') * $produk['stok']
            ];

            if($gambarName) {
                if($produk['gambar'] && file_exists(FCPATH.'uploads/'.$produk['gambar'])){
                    unlink(FCPATH.'uploads/'.$produk['gambar']);
                }
                $data['gambar'] = $gambarName;
            }

            $model->update($id, $data);
            return $this->response->setJSON(['status' => 'updated']);
        }

        $stok = $this->request->getPost('stok');
        $harga = $this->request->getPost('harga');

        $data = [
            'nama_produk' => $this->request->getPost('nama_produk'),
            'stok' => $stok,
            'sisa_stok' => $stok,
            'harga' => $harga,
            'total_harga' => $stok * $harga,
            'gambar' => $gambarName
        ];

        $model->insert($data);
        return $this->response->setJSON(['status' => 'saved']);
    }

    public function getProduk($id)
    {
        $model = new ProdukModel();
        return $this->response->setJSON($model->find($id));
    }

    public function delete($id)
    {
        $model = new ProdukModel();
        $produk = $model->find($id);

        if($produk['gambar'] && file_exists(FCPATH.'uploads/'.$produk['gambar'])){
            unlink(FCPATH.'uploads/'.$produk['gambar']);
        }

        $model->delete($id);
        return $this->response->setJSON(['status'=>'deleted']);
    }
}
