<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <h1 class="page-title">Data Transaksi</h1>
</div>

<div class="row row-cards row-deck">
    <div class="col-12">

        <!-- Form Transaksi -->
        <form class="card" id="transaksiForm">
            <div class="card-status bg-blue"></div>
            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-4">
                        <label for="produkSelect" class="form-label">Pilih Produk</label>
                        <select name="produk_id" id="produkSelect" class="form-control" required>
                            <option value="">-- Pilih Produk --</option>
                            <?php foreach($produk as $p): ?>
                                <option value="<?= $p['id'] ?>" data-harga="<?= $p['harga'] ?>" data-stok="<?= $p['sisa_stok'] ?>">
                                    <?= $p['nama_produk'] ?> (Stok: <?= $p['sisa_stok'] ?>, Rp <?= number_format($p['harga'],0,',','.') ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="jenisTransaksi" class="form-label">Jenis Transaksi</label>
                        <select name="jenis_transaksi" id="jenisTransaksi" class="form-control" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="masuk">Masuk</option>
                            <option value="keluar">Keluar</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="jumlahInput" class="form-label">Jumlah</label>
                        <input type="number" name="jumlah" id="jumlahInput" class="form-control" value="1" min="1" required>
                    </div>

                    <div class="col-md-4">
                        <label for="totalHarga" class="form-label">Total Harga</label>
                        <input type="text" id="totalHarga" class="form-control" placeholder="Rp 0" readonly>
                    </div>

                </div>
            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-success"><i class="fe fe-save"></i></button>
                <button type="button" class="btn btn-gray" id="resetBtn"><i class="fa fa-minus-square"></i></button>
            </div>
        </form>

        <!-- TABEL TRANSAKSI -->
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Daftar Transaksi</h3>
                <a href="<?= base_url('transaksi/export') ?>" class="btn btn-primary btn-sm">
    <span class="me-2"><i class="fe fe-download"></i></span> Export
</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-outline table-vcenter text-nowrap card-table" id="transaksiTable">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Produk</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-right">Total Harga</th>
                            <th class="text-center">Tanggal</th>
                            <th class="text-center">Jenis</th>
                            <th class="text-center"><i class="fe fe-settings"></i></th>
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
                            <tr data-id="<?= $t['id'] ?>">
                                <td class="text-center"><?= $no++; ?></td>
                                <td class="text-center"><?= $t['nama_produk'] ?></td>
                                <td class="text-center"><?= $t['jumlah'] ?></td>
                                <td class="text-right">Rp. <?= number_format($t['total_harga'],0,',','.') ?></td>
                                <td class="text-center"><?= date('d-m-Y', strtotime($t['created_at'])) ?></td>
                                <td class="text-center"><?= ucfirst($t['jenis_transaksi']) ?></td>
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
const form = document.getElementById('transaksiForm');
const produkSelect = document.getElementById('produkSelect');
const jenisTransaksi = document.getElementById('jenisTransaksi');
const jumlahInput = document.getElementById('jumlahInput');
const totalHarga = document.getElementById('totalHarga');
const resetBtn = document.getElementById('resetBtn');
const notyf = new Notyf({duration:4000, position:{x:'right',y:'top'}});

// ===== Hitung Total Harga =====
function updateTotalHarga(){
    const option = produkSelect.selectedOptions[0];
    if(!option.value) { totalHarga.value = "Rp 0"; return; }
    const harga = parseFloat(option.dataset.harga);
    const jumlah = parseInt(jumlahInput.value) || 0;
    totalHarga.value = "Rp " + (harga * jumlah).toLocaleString('id-ID');
}

produkSelect.addEventListener('change', updateTotalHarga);
jumlahInput.addEventListener('input', updateTotalHarga);

// ===== Reset Form =====
resetBtn.addEventListener('click', ()=>{
    produkSelect.value = '';
    jenisTransaksi.value = '';
    jumlahInput.value = 1;
    totalHarga.value = "Rp 0";
});

// ===== Submit Form =====
form.addEventListener('submit', e=>{
    e.preventDefault();
    const option = produkSelect.selectedOptions[0];
    const stok = parseInt(option.dataset.stok);
    const jumlah = parseInt(jumlahInput.value) || 0;
    const jenis_transaksi = jenisTransaksi.value;

    if(jenis_transaksi === 'keluar' && jumlah > stok){
        notyf.error("Jumlah melebihi stok!");
        return;
    }

    fetch('<?= base_url('transaksi/saveAdmin') ?>',{
        method:'POST',
        body:new FormData(form)
    }).then(res=>res.json())
    .then(data=>{
        let errors=[], success=[];
        data.forEach(item=>{
            if(item.status==='error') {
                errors.push(item.message);
            } else if(item.status==='saved') {
                success.push('Transaksi berhasil disimpan');
            } else if(item.status==='updated') {
                success.push('Transaksi berhasil diubah');
            }
        });
        if(errors.length>0) notyf.error(errors.join("\n"));
        if(success.length>0) notyf.success(success.join("\n"));
        if(success.length>0) setTimeout(()=>window.location.reload(),1000);
    }).catch(err=>{
        console.error(err);
        notyf.error("Terjadi kesalahan saat menyimpan transaksi.");
    });
});

// ===== Edit/Delete Transaksi =====
document.getElementById('transaksiTable').addEventListener('click', e=>{
    const btn = e.target.closest('button');
    if(!btn) return;
    const tr = btn.closest('tr');
    const id = tr.dataset.id;

    // Edit
    if(btn.classList.contains('editBtn')){
        fetch('<?= base_url('transaksi/getTransaksi') ?>/'+id)
        .then(res=>res.json())
        .then(data=>{
            produkSelect.value = data.produk_id;
            jenisTransaksi.value = data.jenis_transaksi;
            jumlahInput.value = data.jumlah;
            updateTotalHarga();
        });
    }

    // Delete
    if(btn.classList.contains('deleteBtn')){
        if(confirm('Hapus transaksi ini?')){
            fetch('<?= base_url('transaksi/delete') ?>/'+id)
            .then(res=>res.json())
            .then(data=>{
                if(data.status==='deleted'){
                    notyf.success("Transaksi berhasil dihapus");
                    tr.remove();
                     setTimeout(() => location.reload(), 200);
                } else {
                    notyf.error(data.message);
                }
            });
        }
    }
});
</script>

<?= $this->endSection() ?>
