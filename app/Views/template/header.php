<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php if ($title_section = $this->renderSection('title')): ?>
    <title><?= $title_section; ?> - Sweet Treats Donut Shop</title>
  <?php else: ?>
    <title><?= (isset($statusCode)) ? ($statusCode) : ((isset($title)) ? $title : 'Home'); ?> - Sweet Treats Donut Shop</title>
  <?php endif; ?>

  <link rel="stylesheet" href="<?= asset_url(); ?>assets/plugins/bootstrap-4.6.2/css/bootstrap.min.css">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= asset_url(); ?>assets/plugins/fontawesome-free/css/all.min.css">

  <link rel="stylesheet" href="<?= asset_url(); ?>assets/dist/css/style.css">

  <link rel="stylesheet" href="<?= asset_url(); ?>assets/plugins/select2/css/select2.min.css">

  <link rel="stylesheet" href="<?= asset_url(); ?>assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

  <link rel="stylesheet" href="<?= asset_url(); ?>assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= asset_url(); ?>assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= asset_url(); ?>assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

  <meta name="csrf-token-name" content="<?= csrf_token() ?>">
  <meta name="csrf-token-value" content="<?= csrf_hash() ?>">

  <?= $this->renderSection('head'); ?>
</head>