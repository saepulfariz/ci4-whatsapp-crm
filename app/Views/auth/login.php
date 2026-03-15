<?= $this->extend('template/auth') ?>

<?= $this->section('title') ?>Login POS Donat<?= $this->endSection() ?>

<?= $this->section('content') ?>

<body style="background: linear-gradient(135deg,#ffb6c1,#ffe4e1); min-height:100vh; display:flex; align-items:center; justify-content:center;">

    <div class="card" style="width:100%; max-width:400px; border-radius:15px; box-shadow:0 10px 25px rgba(0,0,0,0.2);">
        <div class="card-body">

            <div class="text-center mb-4">
                <div style="font-size:28px; font-weight:bold;">🍩 POS Donat</div>
                <small class="text-muted">Silakan login ke sistem kasir</small>
            </div>

            <!-- Alert Errors -->
            <?php if (session('error') !== null) : ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <?= session('error') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php elseif (session('errors') !== null) : ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <?php if (is_array(session('errors'))) : ?>
                        <?php foreach (session('errors') as $error) : ?>
                            <?= $error ?><br>
                        <?php endforeach ?>
                    <?php else : ?>
                        <?= session('errors') ?>
                    <?php endif ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif ?>

            <?php if (session('message') !== null) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session('message') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif ?>

            <!-- Login Form -->
            <form action="<?= base_url('login'); ?>" method="post">
                <?= csrf_field(); ?>
                <div class="form-group">
                    <input type="text" class="form-control" name="email" id="email" placeholder="<?= lang('Auth.email') ?>" value="<?= old('email'); ?>">
                    <div class="text-danger"><?= validation_show_error('email'); ?></div>
                </div>

                <div class="form-group">
                    <input type="password" class="form-control" name="password" id="password" placeholder="<?= lang('Auth.password') ?>">
                    <div class="text-danger"><?= validation_show_error('password'); ?></div>
                </div>

                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" <?php if (old('remember')): ?> checked<?php endif ?> name="remember" id="remember">
                    <label class="form-check-label" for="remember"><?= lang('Auth.rememberMe') ?></label>
                </div>

                <button type="submit" class="btn btn-block" style="background:#ff6b81; color:white;">Login</button>
            </form>

            <?php if (setting('Auth.allowRegistration')) : ?>
                <p class="mt-3 text-center">
                    <a href="<?= url_to('register') ?>"><?= lang('Auth.needAccount') ?> - <?= lang('Auth.register') ?></a>
                </p>
            <?php endif ?>

            <div class="text-center mt-3">
                <small class="text-muted">POS Donat © 2026</small>
            </div>

        </div>
    </div>

</body>

<?= $this->endSection('content') ?>