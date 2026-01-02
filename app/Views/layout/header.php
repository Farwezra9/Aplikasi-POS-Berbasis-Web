<div class="header py-4">
    <div class="container">
        <div class="d-flex">
            <a class="header-brand" href="<?= base_url() ?>">
                <img src="<?= base_url('assets/images/logo2.png') ?>" class="header-brand-img" alt="Logo">
            </a>
            <div class="d-flex order-lg-2 ml-auto">
                <div class="dropdown">
                    <a href="#" class="nav-link pr-0 leading-none" data-toggle="dropdown">
                        <span class="avatar" style="background-image: url('<?= base_url('assets/images/logo.png') ?>')"></span>
                        <span class="ml-2 d-none d-lg-block">
                            <span class="text-default"><?= session()->get('username')?></span>
                            <small class="text-muted d-block mt-1"><?= session()->get('role') ?? 'Administrator' ?></small>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                        <a class="dropdown-item" href="<?= base_url('logout') ?>"><i class="dropdown-icon fe fe-log-out"></i> Log out</a>
                    </div>
                </div>
            </div>
            <a href="#" class="header-toggler d-lg-none ml-3 ml-lg-0" data-toggle="collapse" data-target="#headerMenuCollapse">
                <span class="header-toggler-icon"></span>
            </a>
        </div>
    </div>
</div>
<?php 
$segment = service('uri')->getSegment(1); 
$role = session()->get('role'); 
?>
 <?php if ($role === 'admin'):?>
<div class="header collapse d-lg-flex p-0" id="headerMenuCollapse">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg order-lg-first">
                <ul class="nav nav-tabs border-0 flex-column flex-lg-row">
                    <li class="nav-item">
                        <a href="<?= base_url('dashboard') ?>" class="nav-link <?= ($segment == 'dashboard' || $segment == '') ? 'active' : '' ?>">
                            <i class="fe fe-home"></i> Home
                        </a>
                    </li>
                        <li class="nav-item">
                            <a href="<?= base_url('produk') ?>" class="nav-link <?= $segment == 'produk' ? 'active' : '' ?>">
                                <i class="fe fe-shopping-bag"></i> Produk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('transaksi') ?>" class="nav-link <?= $segment == 'transaksi' ? 'active' : '' ?>">
                                <i class="fe fe-dollar-sign"></i> Transaksi
                            </a>
                        </li>
                   
                </ul>
            </div>
        </div>
    </div>
</div>
 <?php endif; ?>
