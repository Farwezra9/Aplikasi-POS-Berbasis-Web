<?php

namespace App\Models;
use CodeIgniter\Model;

class ProdukModel extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_produk', 'gambar', 'stok', 'sisa_stok', 'harga', 'total_harga'];
    protected $useTimestamps = true;
}