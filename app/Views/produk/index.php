<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title">Data Produk</h1>
</div>

<div class="row row-cards row-deck">
    <div class="col-12">
        <!-- Form Produk -->
        <form class="card" id="produkForm" enctype="multipart/form-data">
            <div class="card-status bg-blue"></div>
            <input type="hidden" name="id" id="id">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Nama Produk</label>
                            <input type="text" class="form-control" name="nama_produk" id="nama_produk" placeholder="Nama Produk" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Jumlah</label>
                            <input type="number" class="form-control" name="stok" id="stok" placeholder="Stok" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Harga Satuan</label>
                            <input type="number" class="form-control" name="harga" id="harga" placeholder="Harga" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Gambar Produk</label>
                            <input type="file" class="form-control" name="gambar" id="gambar" accept="image/*">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-success"><i class="fe fe-save"></i></button>
                <button type="button" class="btn btn-gray" id="resetBtn"><i class="fa fa-minus-square"></i></button>
            </div>
        </form>

        <!-- Tabel Produk -->
<div class="card mt-3">
    <div class="table-responsive">
        <table class="table table-hover table-outline table-vcenter text-nowrap card-table">
            <thead>
                <tr>
                    <th class="text-center w-1">#</th>
                    <th>Produk</th>
                    <th class="text-center">Stok</th>
                    <th class="text-center">Sisa</th>
                    <th class="text-right">Harga Satuan</th>
                    <th class="text-right">Total Harga</th>
                    <th class="text-center"><i class="fe fe-settings"></i></th>
                </tr>
            </thead>
            <tbody id="produkTable">
                <?php if (empty($produk)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            Tidak Ada Data Produk Saat Ini
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1;?>
                    <?php foreach($produk as $p): ?>
                        <tr data-id="<?= $p['id'] ?>">
                            <td class="text-center"><?= $no++; ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php if($p['gambar']): ?>
                                        <span class="avatar mr-2" style="width:40px; height:40px; background-size: cover; background-position: center; background-image: url('<?= base_url('uploads/'.$p['gambar']) ?>'); border-radius:5px;"></span>
                                    <?php endif; ?>
                                    <span><?= $p['nama_produk'] ?></span>
                                </div>
                            </td>
                            <td class="text-center"><?= $p['stok'] ?> pcs</td>
                            <td class="text-center"><?= $p['sisa_stok'] ?> pcs</td>
                            <td class="text-right">Rp. <?= number_format($p['harga'], 0, ',', '.') ?></td>
                            <td class="text-right">Rp. <?= number_format($p['total_harga'], 0, ',', '.') ?></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-icon btn-warning editBtn"><i class="fe fe-edit"></i></button>
                                <button type="button" class="btn btn-icon btn-danger deleteBtn"><i class="fe fe-trash-2"></i></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

    </div>
</div>

<script>
    const form = document.getElementById('produkForm');
    const resetBtn = document.getElementById('resetBtn');
    const tableBody = document.getElementById('produkTable');

    // Notyf
    const notyf = new Notyf({
        duration: 4000,
        position: { x: 'right', y: 'top' }
    });

    // Reset form
    resetBtn.addEventListener('click', () => {
        form.reset();
        document.getElementById('id').value = '';
        document.getElementById('stok').disabled = false;
    });

    // Submit form (Tambah/Edit)
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(form);

        try {
            await fetch('<?= base_url('produk/save') ?>', {
                method: 'POST',
                body: formData
            });

            notyf.success("Produk berhasil disimpan!");
            setTimeout(() => location.reload(), 500);

        } catch (err) {
            notyf.error("Gagal menyimpan data!");
        }
    });

    // Edit / Delete
    tableBody.addEventListener('click', async function(e) {
        const btn = e.target.closest('button');
        if (!btn) return;

        const tr = btn.closest('tr');
        const id = tr.dataset.id;

        // === EDIT ===
        if (btn.classList.contains('editBtn')) {
            try {
                let res = await fetch('<?= base_url('produk/getProduk') ?>/' + id);
                let data = await res.json();

                document.getElementById('id').value = data.id;
                document.getElementById('nama_produk').value = data.nama_produk;
                document.getElementById('stok').value = data.stok;
                document.getElementById('harga').value = data.harga;
                document.getElementById('stok').disabled = true;
            } catch (err) {
                notyf.error("Gagal memuat data!");
            }
        }

        // === DELETE ===
        if (btn.classList.contains('deleteBtn')) {
            if (!confirm('Yakin ingin menghapus data ini?')) return;

            try {
                await fetch('<?= base_url('produk/delete') ?>/' + id);
                notyf.success("Produk berhasil dihapus!");
                setTimeout(() => location.reload(), 500);
            } catch (err) {
                notyf.error("Gagal menghapus data!");
            }
        }
    });
</script>

<?= $this->endSection() ?>
