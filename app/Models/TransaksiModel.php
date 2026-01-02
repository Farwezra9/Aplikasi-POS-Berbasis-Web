<?php

namespace App\Models;
use CodeIgniter\Model;

class TransaksiModel extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'id';
    protected $allowedFields = ['produk_id', 'jenis_transaksi', 'jumlah', 'total_harga'];
    protected $useTimestamps = true;
}

