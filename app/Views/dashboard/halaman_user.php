<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title">Transaksi</h1>
    <!-- Search Produk -->
    <div class="col-lg-3 ml-auto">
        <form class="input-icon my-3 my-lg-0">
            <input type="search" id="searchProduk" class="form-control header-search" placeholder="Search&hellip;" tabindex="1">
            <div class="input-icon-addon"><i class="fe fe-search"></i></div>
        </form>
    </div>
</div>

<div class="row row-cards row-deck">
    <div class="col-12">

        <!-- Form Transaksi -->
<form class="card" id="transaksiForm" style="display:none;">
    <div class="card-status bg-blue"></div>
    <div class="card-body">
        <table id="inputTable" class="table table-hover table-outline table-vcenter text-nowrap card-table"></table>
        <div id="checkoutSection" class="mt-3 d-flex justify-content-end align-items-center" style="display:none;">
            <p class="mb-0" style="font-size: 1rem; font-weight: 500;">
                Total Pembayaran : 
                <span id="totalPembayaran" style="color: green; font-weight: bold; font-size: 1.25rem;">Rp 0</span>
            </p>
        </div>
    </div>

    <div class="card-footer text-right">
        <button type="submit" class="btn btn-success"><i class="fe fe-shopping-cart"></i></button>
        <button type="button" class="btn btn-gray" id="resetBtn"><i class="fa fa-minus-square"></i></button>
    </div>
</form>


        <!-- TEMPLATE ROW -->
        <template id="rowTemplate">
            <tr>
                <td>
                    <input type="hidden" name="id[]" class="idInput" value="">
                    <input type="hidden" name="produk_id[]" class="produkIdInput" value="">
                    <input type="text" class="produkNamaDisplay form-control" placeholder="Produk" readonly>
                </td>
                <td><input type="number" name="jumlah[]" class="jumlahInput form-control" placeholder="Jumlah" min="1" value="1" required></td>
                <td><input type="text" class="totalHargaDisplay form-control" placeholder="Total Harga" readonly></td>
                <td><button type="button" class="btn btn-danger removeRow"><i class="fa fa-remove"></i></button></td>
            </tr>
        </template>

        <!-- Produk Card -->
        <div class="row row-cards row-deck mt-4" id="produkCardContainer">
            <?php foreach($produk as $p): ?>
            <div class="col-sm-6 col-xl-3 produkCardWrapper">
                <div class="card produkCard" 
                     data-id="<?= $p['id'] ?>" 
                     data-nama="<?= $p['nama_produk'] ?>" 
                     data-harga="<?= $p['harga'] ?>" 
                     data-stok="<?= $p['sisa_stok'] ?>">
                    <img class="card-img-top" src="<?= base_url('uploads/'.$p['gambar']) ?>" alt="<?= $p['nama_produk'] ?>" style="object-fit: cover; height: 200px;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= $p['nama_produk'] ?></h5>

                        <!-- Wrapper flex untuk stok & harga + tombol -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="card-text mb-0">Stok: <?= $p['sisa_stok'] ?></p>
                                <p class="card-text mb-0">Rp <?= number_format($p['harga'],0,',','.') ?></p>
                            </div>
                            <button class="btn btn-link pilihProdukBtn" 
                                    <?= $p['sisa_stok'] <= 0 ? 'disabled' : '' ?> 
                                    title="<?= $p['sisa_stok'] > 0 ? 'Pilih Produk' : 'Stok Habis' ?>">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- TABEL TRANSAKSI -->
        <div class="card mt-4">
            <div class="card-header">
                <h3 class="card-title">Daftar Transaksi</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-outline table-vcenter text-nowrap card-table" id="transaksiTable">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-left">Produk</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-right">Total Harga</th>
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
                            <tr data-id="<?= $t['id'] ?>">
                                <td class="text-center"><?= $no++; ?></td>
                                <td class="text-left"><?= $t['nama_produk'] ?></td>
                                <td class="text-center"><?= $t['jumlah'] ?></td>
                                <td class="text-right">Rp. <?= number_format($t['total_harga'],0,',','.') ?></td>
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

<script>
const form = document.getElementById('transaksiForm');
const inputTable = document.getElementById('inputTable');
const resetBtn = document.getElementById('resetBtn');
const template = document.getElementById('rowTemplate').content;
const totalPembayaranEl = document.getElementById('totalPembayaran');
const checkoutSection = document.getElementById('checkoutSection');
const notyf = new Notyf({
    duration: 4000,
    position: { x: 'right', y: 'top' }
});

resetBtn.addEventListener('click', () => {
    form.reset();
    inputTable.innerHTML = "";
    totalPembayaranEl.textContent = "Rp 0";
    checkoutSection.style.display = "none";
    form.style.display = "none";
});

// ===== UNTUK MUNCULKAN FORM =====
function showForm(){
    form.style.display = 'block';
}

// Search Produk
const searchInput = document.getElementById('searchProduk');
const produkCards = document.querySelectorAll('.produkCardWrapper');

searchInput.addEventListener('input', function() {
    const query = this.value.toLowerCase();

    produkCards.forEach(wrapper => {
        const card = wrapper.querySelector('.produkCard');
        const nama = card.dataset.nama.toLowerCase();

        wrapper.style.display = nama.includes(query) ? '' : 'none';
    });
});

// ===== Hitung Total Pembayaran =====
function updateTotalPembayaran(){
    let total = 0;
    inputTable.querySelectorAll('tr').forEach(row=>{
        const val = row.querySelector('.totalHargaDisplay').value.replace(/[Rp\s.]/g,'');
        total += parseInt(val) || 0;
    });
    totalPembayaranEl.textContent = "Rp " + total.toLocaleString('id-ID');
    checkoutSection.style.display = inputTable.rows.length > 0 ? 'block' : 'none';
}

// ===== Listener pada row baru =====
function attachListeners(row){
    row.querySelector('.removeRow').addEventListener('click', ()=>{
        row.remove();
        updateTotalPembayaran();
    });

    row.querySelector('.jumlahInput').addEventListener('input', function(){
        const productId = row.querySelector('.produkIdInput').value;
        const card = document.querySelector(`.produkCard[data-id="${productId}"]`);
        const harga = parseFloat(card.dataset.harga);
        const jumlah = parseInt(this.value) || 0;

        row.querySelector('.totalHargaDisplay').value =
            "Rp " + (harga * jumlah).toLocaleString('id-ID');

        updateTotalPembayaran();
    });
}

// ===== Pilih Produk =====
document.querySelectorAll('.pilihProdukBtn').forEach(btn => {
    btn.addEventListener('click', function(){
        showForm();

        const card = this.closest('.produkCard');
        const stok = parseInt(card.dataset.stok);
        if(stok <= 0){
            notyf.error("Stok produk ini habis!");
            return;
        }

        const id = card.dataset.id;
        const nama = card.dataset.nama;
        const harga = parseFloat(card.dataset.harga);

        const existingRow = Array.from(inputTable.rows)
            .find(r => r.querySelector('.produkIdInput').value == id);

        if(existingRow){
            const jumlahInput = existingRow.querySelector('.jumlahInput');
            jumlahInput.value = parseInt(jumlahInput.value) + 1;
            existingRow.querySelector('.totalHargaDisplay').value =
                "Rp " + (harga * jumlahInput.value).toLocaleString('id-ID');
            updateTotalPembayaran();
            return;
        }

        const newRow = template.cloneNode(true).querySelector('tr');
        newRow.querySelector('.produkIdInput').value = id;
        newRow.querySelector('.produkNamaDisplay').value = nama;
        newRow.querySelector('.jumlahInput').value = 1;
        newRow.querySelector('.totalHargaDisplay').value =
            "Rp " + harga.toLocaleString('id-ID');

        inputTable.appendChild(newRow);
        attachListeners(newRow);
        updateTotalPembayaran();
    });
});

// ===== Submit Form =====
form.addEventListener('submit', function(e){
    e.preventDefault();
    fetch('<?= base_url('transaksi/saveUser') ?>', {
        method: 'POST',
        body: new FormData(form)
    })
    .then(res=>res.json())
    .then(data=>{
        let errors = [];
        let success = [];
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
        if(success.length>0) setTimeout(()=>window.location.reload(), 1000);
    })
    .catch(err=>{
        console.error(err);
        notyf.error("Terjadi kesalahan saat menyimpan transaksi.");
    });
});
</script>

<?= $this->endSection() ?>
