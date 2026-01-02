<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <h1 class="page-title">Dashboard Admin</h1>
</div>

<div class="row row-cards row-deck">
    <div class="col-6 col-sm-4 col-lg-4">
        <div class="card text-center">
            <span class="stamp stamp-md bg-yellow mr">
                <i class="fa fa-shopping-basket"></i>
            </span>
            <div class="card-body">
                <div class="h5">Jenis Produk</div>
                <div class="display-4 font-weight-bold mb-4"><?= $jumlahProduk ?></div>
            </div>
        </div>
    </div>

    <div class="col-6 col-sm-4 col-lg-4">
        <div class="card text-center">
            <span class="stamp stamp-md bg-green mr">
                <i class="fe fe-shopping-bag"></i>
            </span>
            <div class="card-body">
                <div class="h5">Total Produk Terjual</div>
                <div class="display-4 font-weight-bold mb-4"><?= $totalProdukTerjual ?></div>
            </div>
        </div>
    </div>

    <div class="col-6 col-sm-4 col-lg-4">
        <div class="card text-center">
            <span class="stamp stamp-md bg-blue mr">
                <i class="fe fe-dollar-sign"></i>
            </span>
            <div class="card-body">
                <div class="h5">Total Pendapatan</div>
                <div class="display-4 font-weight-bold mb-4">Rp <?= number_format($totalPendapatan,0,',','.') ?></div>
            </div>
        </div>
    </div>

    <!-- Riwayat Transaksi -->
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Riwayat Transaksi</h4>
            </div>
            <div class="card-body o-auto" style="height: 15rem; overflow-y: auto;">
                <table class="table table-hover table-sm mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Produk</th>
                            <th class="text-center">Jenis</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-right">Total</th>
                            <th class="text-center">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php if (empty($transaksi)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Tidak Ada Data Transaksi Saat Ini
                                </td>
                            </tr>
                        <?php else: ?>
                        <?php $no = 1; ?>
                        <?php foreach($transaksi as $t): ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td class="text-center"><?= $t['nama_produk'] ?></td>
                            <td class="text-center"><?= ucfirst($t['jenis_transaksi']) ?></td>
                            <td class="text-center"><?= $t['jumlah'] ?></td>
                            <td class="text-right">Rp <?= number_format($t['total_harga'],0,',','.') ?></td>
                            <td class="text-center"><?= date('d-m-Y', strtotime($t['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                         <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
