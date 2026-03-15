<?php


// $data_user = getProfile();


?>
<aside class="st-sidebar">
    <div class="st-sidebar-header">
        <h1 class="logo">ST</h1>
        <p class="logo-text">Sweet Treats</p>
    </div>
    <nav class="st-sidebar-nav">
        <?php if (isset($sidebarMenus) && is_array($sidebarMenus)): ?>
            <?= view_cell('\App\Libraries\MenuCells::renderMenu', ['menus' => $sidebarMenus]) ?>
        <?php endif; ?>
    </nav>
</aside>
<!-- Sidebar Navigation -->
<!-- <aside class="st-sidebar">
    <div class="st-sidebar-header">
        <h1 class="logo">ST</h1>
        <p class="logo-text">Sweet Treats</p>
    </div>
    <nav class="st-sidebar-nav">
        <button class="st-nav-item active" data-page="dashboard">
            <span class="icon">📊</span>
            <span>Dashboard</span>
        </button>
        <button class="st-nav-item" data-page="stock">
            <span class="icon">📋</span>
            <span>Stock</span>
        </button>
        <button class="st-nav-item" data-page="sales">
            <span class="icon">💳</span>
            <span>Sales</span>
        </button>
        <button class="st-nav-item" data-page="reports">
            <span class="icon">📈</span>
            <span>Reports</span>
        </button>
        <button class="st-nav-item st-submenu-toggle" data-submenu="masterdata">
            <span class="icon">⚙️</span>
            <span>Master Data</span>
            <span class="st-submenu-arrow">▼</span>
        </button>
        <div class="st-submenu" id="masterdata-submenu">
            <button class="st-nav-subitem" data-page="master-product">
                <span>📦 Master Product</span>
            </button>
            <button class="st-nav-subitem" data-page="master-category">
                <span>🏷️ Master Category</span>
            </button>
            <button class="st-nav-subitem" data-page="master-customer">
                <span>👥 Customer / WhatsApp</span>
            </button>
        </div>
        <button class="st-nav-item" data-page="broadcast">
            <span class="icon">📱</span>
            <span>WhatsApp Broadcast</span>
        </button>
    </nav>
    <div class="st-sidebar-footer">
        <p class="user-info">👤 Admin User</p>
    </div>
    </aside> -->