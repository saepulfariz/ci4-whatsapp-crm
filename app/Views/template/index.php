<?= $this->include('template/header'); ?>

<body>
  <div class="app">
    <div class="container">

      <!-- Main Sidebar Container -->
      <?= $this->include('template/sidebar'); ?>

      <main class="main-content">
          <?= $this->include('template/topbar'); ?>
          <div class="content-area">
              <?= $this->renderSection('content'); ?>
          </div>
      </main>
  </div>


  <?= $this->include('template/footer'); ?>
</body>

</html>