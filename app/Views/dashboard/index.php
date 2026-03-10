<?= $this->extend('template/index') ?>

<?= $this->section('content') ?>
<!-- Summary Cards -->
<div class="cards-grid">
    <div class="card">
        <div class="card-header">Today's Sales</div>
        <div class="card-value">Rp 2,450,000</div>
        <div class="card-subtitle">+12% from yesterday</div>
    </div>
    <div class="card">
        <div class="card-header">Total Transactions</div>
        <div class="card-value">24</div>
        <div class="card-subtitle">+3 from yesterday</div>
    </div>
    <div class="card">
        <div class="card-header">Best-Selling Product</div>
        <div class="card-value">Sugar Donut</div>
        <div class="card-subtitle">15 units sold today</div>
    </div>
    <div class="card">
        <div class="card-header">Low Stock Alert</div>
        <div class="card-value">3</div>
        <div class="card-subtitle">Products below minimum</div>
    </div>
    <div class="card">
        <div class="card-header">Weekly Revenue</div>
        <div class="card-value">Rp 18,500,000</div>
        <div class="card-subtitle">Last 7 days</div>
    </div>
    <div class="card">
        <div class="card-header">Est. Gross Profit</div>
        <div class="card-value">Rp 9,250,000</div>
        <div class="card-subtitle">49.9% margin</div>
    </div>
</div>

<!-- Charts Section -->
<div class="charts-container">
    <div class="chart-box">
        <h3>Sales This Week</h3>
        <div class="simple-chart">
            <div class="chart-bar" style="height: 60%;"><span>Mon</span></div>
            <div class="chart-bar" style="height: 75%;"><span>Tue</span></div>
            <div class="chart-bar" style="height: 85%;"><span>Wed</span></div>
            <div class="chart-bar" style="height: 70%;"><span>Thu</span></div>
            <div class="chart-bar" style="height: 90%;"><span>Fri</span></div>
            <div class="chart-bar" style="height: 95%;"><span>Sat</span></div>
            <div class="chart-bar" style="height: 80%;"><span>Sun</span></div>
        </div>
    </div>

    <div class="chart-box">
        <h3>Product Category Distribution</h3>
        <div class="pie-legend">
            <div><span class="legend-color" style="background: #FF6B6B;"></span> Donuts (45%)</div>
            <div><span class="legend-color" style="background: #4ECDC4;"></span> Beverages (35%)</div>
            <div><span class="legend-color" style="background: #FFE66D;"></span> Pastries (20%)</div>
        </div>
    </div>
</div>

<!-- Latest Transactions -->
<div class="section-box">
    <h3>Latest Transactions</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Date</th>
                <th>Transaction No</th>
                <th>Total Items</th>
                <th>Total Payment</th>
                <th>Payment Method</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>10 Mar 2026, 14:30</td>
                <td>TRX-20260310-001</td>
                <td>3</td>
                <td>Rp 85,000</td>
                <td>Cash</td>
                <td><span class="badge active">Completed</span></td>
            </tr>
            <tr>
                <td>2</td>
                <td>10 Mar 2026, 14:15</td>
                <td>TRX-20260310-002</td>
                <td>2</td>
                <td>Rp 120,000</td>
                <td>Transfer</td>
                <td><span class="badge active">Completed</span></td>
            </tr>
            <tr>
                <td>3</td>
                <td>10 Mar 2026, 14:00</td>
                <td>TRX-20260310-003</td>
                <td>5</td>
                <td>Rp 220,000</td>
                <td>QRIS</td>
                <td><span class="badge active">Completed</span></td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Low Stock Alert -->
<div class="section-box">
    <h3>Low Stock Alert</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Product Name</th>
                <th>Current Stock</th>
                <th>Minimum Stock</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Cheese Donut</td>
                <td>5</td>
                <td>10</td>
                <td><span class="badge low">Low Stock</span></td>
            </tr>
            <tr>
                <td>2</td>
                <td>Iced Tea</td>
                <td>8</td>
                <td>15</td>
                <td><span class="badge low">Low Stock</span></td>
            </tr>
            <tr>
                <td>3</td>
                <td>Thai Tea</td>
                <td>3</td>
                <td>10</td>
                <td><span class="badge low">Low Stock</span></td>
            </tr>
        </tbody>
    </table>
</div>
<?= $this->endSection('content') ?>