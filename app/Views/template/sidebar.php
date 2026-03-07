<?php


// $data_user = getProfile();


?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="<?= base_url('dashboard'); ?>" class="brand-link">
    <!-- change img to font fas fa-warehouse -->
    <!-- <i class="nav-icon fas fa-warehouse img-circle elevation-3" style="margin-left: 1rem;margin-right: .5rem;max-height: 33px;width:auto;"></i> -->
    <img src="<?= asset_url(); ?>assets/dist/img/AdminLTELogo.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light"><?= APP_NAME; ?></span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
      <div class="image">
        <img src="<?= asset_url(); ?>assets/dist/img/user.png" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info" style="padding-bottom: 0px">
        <a href="#" class="d-block text-capitalize"><?= getProfile()->name; ?></a>
        <small class="text-white"><?= getProfile()->username; ?></small>
      </div>
    </div>



    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->

        <?php if (isset($sidebarMenus) && is_array($sidebarMenus)): ?>
          <?= view_cell('\App\Libraries\MenuCells::renderMenu', ['menus' => $sidebarMenus]) ?>
        <?php endif; ?>


      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>