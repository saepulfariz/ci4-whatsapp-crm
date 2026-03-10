<?php


// $data_user = getProfile();


?>

<!-- Sidebar Navigation -->
<aside class="sidebar">
    <div class="sidebar-header">
        <h1 class="logo">ST</h1>
        <p class="logo-text">Sweet Treats</p>
    </div>
    <nav class="sidebar-nav">
        <button class="nav-item active" data-page="dashboard">
            <span class="icon">📊</span>
            <span>Dashboard</span>
        </button>
        <button class="nav-item" data-page="stock">
            <span class="icon">📋</span>
            <span>Stock</span>
        </button>
        <button class="nav-item" data-page="sales">
            <span class="icon">💳</span>
            <span>Sales</span>
        </button>
        <button class="nav-item" data-page="reports">
            <span class="icon">📈</span>
            <span>Reports</span>
        </button>
        <button class="nav-item submenu-toggle" data-submenu="masterdata">
            <span class="icon">⚙️</span>
            <span>Master Data</span>
            <span class="submenu-arrow">▼</span>
        </button>
        <div class="submenu" id="masterdata-submenu">
            <button class="nav-subitem" data-page="master-product">
                <span>📦 Master Product</span>
            </button>
            <button class="nav-subitem" data-page="master-category">
                <span>🏷️ Master Category</span>
            </button>
            <button class="nav-subitem" data-page="master-customer">
                <span>👥 Customer / WhatsApp</span>
            </button>
        </div>
        <button class="nav-item" data-page="broadcast">
            <span class="icon">📱</span>
            <span>WhatsApp Broadcast</span>
        </button>
    </nav>
    <div class="sidebar-footer">
        <p class="user-info">👤 Admin User</p>
    </div>
</aside>


        