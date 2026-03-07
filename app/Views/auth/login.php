<?= $this->extend('template/auth') ?>


<?= $this->section('title') ?><?= lang('Auth.login') ?> <?= $this->endSection() ?>

<?= $this->section('content') ?>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href=""><b>Admin</b>LTE</a>
        </div>
        <!-- /.login-logo -->

        <?php if (session('error') !== null) : ?>
            <!-- <div class="alert alert-danger" role="alert"><?= session('error') ?></div> -->

            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <?= session('error') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php elseif (session('errors') !== null) : ?>
            <!-- <div class="alert alert-danger" role="alert"> -->

            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <?php if (is_array(session('errors'))) : ?>
                    <?php foreach (session('errors') as $error) : ?>
                        <?= $error ?>
                        <br>
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
            <!-- <div class="alert alert-success" role="alert"><?= session('message') ?></div> -->
        <?php endif ?>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg"><?= lang('Auth.login') ?></p>

                <form action="<?= base_url('login'); ?>" method="post">
                    <?= csrf_field(); ?>
                    <div class="input-group">
                        <input type="text" class="form-control" name="email" id="email" placeholder="<?= lang('Auth.email') ?>" value="<?= old('email'); ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 text-danger"><?= validation_show_error('email'); ?></div>
                    <div class="input-group">
                        <input type="password" class="form-control" name="password" id="password" placeholder="<?= lang('Auth.password') ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 text-danger"><?= validation_show_error('password'); ?></div>
                    <div class="row">
                        <div class="col-7">
                            <div class="icheck-primary">
                                <input type="checkbox" <?php if (old('remember')): ?> checked<?php endif ?> name="remember" id="remember">
                                <label for="remember">
                                    <?= lang('Auth.rememberMe') ?>
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-5 text-right">
                            <button type="submit" class="btn btn-primary"><?= lang('Auth.login') ?></button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <?php if (setting('Auth.allowRegistration')) : ?>
                    <p class="mb-0">
                        <a href="<?= url_to('register') ?>" class="text-center"><?= lang('Auth.needAccount') ?> - <?= lang('Auth.register') ?></a>
                    </p>
                <?php endif ?>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->
    <?= $this->endSection('content') ?>