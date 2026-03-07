<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php if ($title_section = $this->renderSection('title')): ?>
    <title><?= $title_section; ?> - CI4 Shield Adminlte</title>
  <?php else: ?>
    <title><?= (isset($statusCode)) ? ($statusCode) : ((isset($title)) ? $title : 'Home'); ?> - CI4 Shield Adminlte</title>
  <?php endif; ?>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= asset_url(); ?>assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="<?= asset_url(); ?>assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">

  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?= asset_url(); ?>assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= asset_url(); ?>assets/dist/css/adminlte.min.css">

  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?= asset_url(); ?>assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">

  <link rel="stylesheet" href="<?= asset_url(); ?>assets/plugins/select2/css/select2.min.css">

  <link rel="stylesheet" href="<?= asset_url(); ?>assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

  <link rel="stylesheet" href="<?= asset_url(); ?>assets/plugins/coloris/dist/coloris.min.css">

  <link rel="stylesheet" href="<?= asset_url(); ?>assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= asset_url(); ?>assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= asset_url(); ?>assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

  <link rel="stylesheet" href="<?= asset_url(); ?>assets/plugins/summernote/summernote-bs4.min.css">

  <meta name="csrf-token-name" content="<?= csrf_token() ?>">
  <meta name="csrf-token-value" content="<?= csrf_hash() ?>">

  <?= $this->renderSection('head'); ?>
</head>