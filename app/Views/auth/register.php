<?= $this->extend('template/auth') ?>

<?= $this->section('title') ?><?= lang('Auth.register') ?> <?= $this->endSection() ?>

<?= $this->section('content') ?>

<body class="hold-transition register-page">
    <div class="register-box">
        <div class="register-logo">
            <a href=""><b>Admin</b>LTE</a>
        </div>

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

        <div class="card">
            <div class="card-body register-card-body">
                <p class="login-box-msg"><?= lang('Auth.register') ?></p>

                <form action="<?= url_to('register') ?>" method="post">
                    <?= csrf_field(); ?>
                    <div class="input-group">
                        <input type="text" class="form-control" name="username" id="username" placeholder="<?= lang('Auth.username') ?>" value="<?= old('username'); ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 text-danger"><?= validation_show_error('username'); ?></div>
                    <div class="input-group">
                        <input type="email" class="form-control" name="email" id="email" placeholder="<?= lang('Auth.email') ?>" id="email" value="<?= old('email') ?>">
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
                    <div class="input-group">
                        <input type="password" class="form-control" name="password_confirm" id="password_confirm" placeholder="<?= lang('Auth.passwordConfirm') ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 text-danger"><?= validation_show_error('password_confirm'); ?></div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="agreeTerms" name="terms" value="agree">
                                <label for="agreeTerms">
                                    I agree to the <a href="#">terms</a>
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block"><?= lang('Auth.register') ?></button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>


                <?= lang('Auth.haveAccount') ?> <a href="<?= url_to('login') ?>" class="text-center"> <?= lang('Auth.login') ?></a>
            </div>
            <!-- /.form-box -->
        </div><!-- /.card -->
    </div>
    <!-- /.register-box -->
    <?= $this->endSection('content') ?>