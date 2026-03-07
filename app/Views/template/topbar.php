<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">

    <?php
    $currentLang = service('request')->getLocale();
    ?>

    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <?php if ($currentLang === 'id'): ?>
          🇮🇩 ID
        <?php else: ?>
          🇺🇸 EN
        <?php endif ?>
        <i class="fas fa-language"></i>
      </a>

      <div class="dropdown-menu dropdown-menu-right">
        <a href="<?= site_url('lang/id') ?>"
          class="dropdown-item <?= $currentLang === 'id' ? 'active' : '' ?>">
          🇮🇩 Indonesia
        </a>

        <a href="<?= site_url('lang/en') ?>"
          class="dropdown-item <?= $currentLang === 'en' ? 'active' : '' ?>">
          🇺🇸 English
        </a>
      </div>
    </li>

    <!-- User Profile Dropdown -->
    <li class="nav-item dropdown user-menu">
      <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
        <img src="<?= asset_url(); ?>assets/dist/img/user.png" class="user-image img-circle elevation-2" alt="User Image">
        <span class="d-none d-md-inline"><?= getProfile()->name; ?></span>
      </a>
      <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <!-- User image -->
        <li class="user-header bg-primary">
          <img src="<?= asset_url(); ?>assets/dist/img/user.png" class="img-circle elevation-2" alt="User Image">
          <p>
            <?= getProfile()->name; ?><br>
            <small><?= getProfile()->email; ?></small>
          </p>
        </li>
        <!-- Menu Body -->
        <li class="user-body">
          <div class="row">
            <div class="col-12 text-center">
              <a href="<?= base_url('profile'); ?>">Profile</a>
            </div>
          </div>
        </li>
        <!-- Menu Footer-->
        <li class="user-footer">
          <a href="<?= base_url('change-password'); ?>" class="btn btn-default btn-flat"><?= temp_lang('app.change-password') ?></a>
          <a href="<?= base_url('logout'); ?>" class="btn btn-default btn-flat float-right"><?= temp_lang('app.logout') ?></a>
        </li>
      </ul>
    </li>
  </ul>
</nav>