<?= $this->include('template/header'); ?>

<body>
  <div class="app">
    <div class="st-container">

      <!-- Main Sidebar Container -->
      <?= $this->include('template/sidebar'); ?>

      <main class="st-main-content">
        <?= $this->include('template/topbar'); ?>
        <div class="st-content-area">
          <?= $this->renderSection('content'); ?>
        </div>
      </main>
    </div>


    <?= $this->include('template/footer'); ?>
</body>

</html>