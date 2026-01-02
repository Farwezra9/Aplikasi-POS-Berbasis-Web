<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en" />

    <title>Login - Yuna Yoghurt</title>

    <!-- FAVICON -->
    <link rel="icon" href="<?= base_url('assets/images/logo.png') ?>" type="image/x-icon"/>

    <!-- GOOGLE FONT -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700">

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- TABLER CSS -->
    <link href="<?= base_url('assets/css/dashboard.css') ?>" rel="stylesheet">

    <!-- TABLER JS -->
    <script src="<?= base_url('assets/js/require.min.js') ?>"></script>
    <script>
        requirejs.config({
            baseUrl: "<?= base_url('assets') ?>"
        });
    </script>

    <script src="<?= base_url('assets/js/dashboard.js') ?>"></script>

    <!-- PLUGINS -->
    <link href="<?= base_url('assets/plugins/charts-c3/plugin.css') ?>" rel="stylesheet">
    <script src="<?= base_url('assets/plugins/charts-c3/plugin.js') ?>"></script>

    <link href="<?= base_url('assets/plugins/maps-google/plugin.css') ?>" rel="stylesheet">
    <script src="<?= base_url('assets/plugins/maps-google/plugin.js') ?>"></script>

    <script src="<?= base_url('assets/plugins/input-mask/plugin.js') ?>"></script>

</head>

<body class="">
<div class="page">
    <div class="page-single">
        <div class="container">
            <div class="row">
                <div class="col col-login mx-auto">

                    <div class="text-center mb-6">
                        <img class="avatar avatar-xxl" src="<?= base_url('assets/images/logo.png') ?>" class="h-6" alt="Logo">
                    </div>

                    <!-- FLASH ERROR -->
                    <?php if(session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <form class="card" action="<?= base_url('login') ?>" method="post">
                        <div class="card-body p-6">

                            <div class="card-title">Yuna Yoghurt</div>

                            <div class="form-group">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control"
                                       placeholder="Masukkan username" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control"
                                       placeholder="Masukkan password" required>
                            </div>

                            <div class="form-footer">
                                <button type="submit" class="btn btn-primary btn-block">Masuk</button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
