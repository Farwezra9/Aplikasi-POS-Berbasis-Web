<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Language" content="en" />
    <meta name="msapplication-TileColor" content="#2d89ef">
    <meta name="theme-color" content="#4188c9">
    <link rel="icon" href="<?= base_url('assets/images/logo.png') ?>" type="image/x-icon"/>
    <link rel="shortcut icon" href="<?= base_url('assets/images/logo.png') ?>" />
    <title>Yuna Yoghurt</title>

    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,300i,400,400i,500,500i,600,600i,700,700i&subset=latin-ext">

    <!-- CSS Tabler -->
    <link href="<?= base_url('assets/css/dashboard.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/plugins/charts-c3/plugin.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/plugins/maps-google/plugin.css') ?>" rel="stylesheet" />

    <!-- JS RequireJS -->
    <script src="<?= base_url('assets/js/require.min.js') ?>"></script>
    <!-- Tambahkan ini di <head> atau sebelum <script> custom kamu -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
<script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>


    <script>
      requirejs.config({ baseUrl: '<?= base_url() ?>' });
    </script>
</head>
<body>
<div class="page">
    <div class="page-main">

        <?= $this->include('layout/header') ?>

        <div class="my-3 my-md-5">
            <div class="container">
                <?= $this->renderSection('content') ?>
            </div> <!-- container -->
        </div> <!-- my-3 my-md-5 -->

    </div> <!-- page-main -->

    <?= $this->include('layout/footer') ?>
</div> <!-- page -->

<!-- JS -->
<script src="<?= base_url('assets/js/dashboard.js') ?>"></script>
<script src="<?= base_url('assets/plugins/charts-c3/plugin.js') ?>"></script>
<script src="<?= base_url('assets/plugins/maps-google/plugin.js') ?>"></script>
<script src="<?= base_url('assets/plugins/input-mask/plugin.js') ?>"></script>
</body>
</html>
