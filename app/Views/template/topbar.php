<header class="st-topbar">
    <div class="st-topbar-left">
        <h2 class="st-page-title"><?= $title; ?></h2>
    </div>
    <div class="st-topbar-right">

        <?php
        $currentLang = service('request')->getLocale();
        ?>

        <!-- not symbol -->
        <div class="nav-item dropdown" class="">
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
        </div>
        <button class="st-btn-icon">
            <a href="<?= base_url('logout'); ?>"><i class="fas fa-sign-out-alt"></i></a>
        </button>
    </div>
</header>