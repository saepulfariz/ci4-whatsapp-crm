<?= $this->extend('template/index') ?>

<?= $this->section('content') ?>
<?php

$can_sales = auth()->user()->can('reports.sales');
$can_stock = auth()->user()->can('reports.stock');
$can_profit = auth()->user()->can('reports.profit');

?>

<h2><?= $title; ?></h2>

<!-- Report Tabs -->
<div class="st-tabs-st-container">
    <?php if ($can_sales): ?>
        <button class="st-tab-btn <?= ($requests['tab'] == 'sales') ? 'active' : ''; ?>" data-tab="sales-report">Sales Report</button>
    <?php endif; ?>
    <?php if ($can_stock): ?>
        <button class="st-tab-btn <?= ($requests['tab'] == 'stock') ? 'active' : ''; ?>" data-tab="stock-report">Stock Report</button>
    <?php endif; ?>
    <?php if ($can_profit): ?>
        <button class="st-tab-btn <?= ($requests['tab'] == 'profit') ? 'active' : ''; ?>" data-tab="profit-report">Profit Report</button>
    <?php endif; ?>
</div>

<!-- Sales Report Tab -->
<?php if ($can_sales): ?>
    <div id="sales-report" class="st-tab-content <?= ($requests['tab'] == 'sales') ? 'active' : ''; ?>">
        <form action="<?= base_url($link) ?>" method="get">
            <input type="hidden" name="tab" value="sales">
            <div class="st-filters-bar">
                <select class="st-input-field" name="report_type">
                    <option value="daily">Daily Report</option>
                    <option value="weekly">Weekly Report</option>
                    <option value="monthly">Monthly Report</option>
                </select>
                <input type="date" class="st-input-field" id="reportDateFilter" name="start_date" placeholder="Start Date" value="<?= $requests['start_date'] ?>">
                <input type="date" class="st-input-field" id="reportDateFilter" name="end_date" placeholder="End Date" value="<?= $requests['end_date'] ?>">
                <select class="st-input-field" id="reportCategoryFilter" name="category_id">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $category): ?>
                        <?php if ($requests['category_id'] == $category->id): ?>
                            <option value="<?= $category->id ?>" selected><?= $category->name ?></option>
                        <?php else: ?>
                            <option value="<?= $category->id ?>"><?= $category->name ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="st-btn st-btn-secondary">Submit</button>
            </div>
        </form>

        <div class="st-section-box">
            <table class="st-data-table" id="salesReportTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Date</th>
                        <th>Transaction No</th>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Qty</th>
                        <th>Selling Price</th>
                        <th>COGS</th>
                        <th>Total Sales</th>
                        <th>Gross Profit</th>
                    </tr>
                </thead>
                <tbody id="salesReportBody">
                    <?php foreach ($report_sales as $sales): ?>
                        <tr>
                            <td><?= $sales->id ?></td>
                            <td><?= date('Y-m-d', strtotime($sales->created_at)) ?></td>
                            <td><?= $sales->transaction_code ?></td>
                            <td><?= $sales->product_name ?></td>
                            <td><?= $sales->category_name ?></td>
                            <td><?= $sales->qty ?></td>
                            <td>Rp. <?= number_format($sales->price, 0, ',', '.') ?></td>
                            <td>Rp. <?= number_format($sales->product_cogs, 0, ',', '.') ?></td>
                            <td>Rp. <?= number_format($sales->total_price, 0, ',', '.') ?></td>
                            <td>Rp. <?= number_format($sales->gross_profit, 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<!-- Stock Report Tab -->
<?php if ($can_stock): ?>
    <div id="stock-report" class="st-tab-content <?= ($requests['tab'] == 'stock') ? 'active' : ''; ?>">
        <div class="st-filters-bar">
            <input type="date" class="st-input-field" id="stockReportDateFilter">
            <select class="st-input-field" id="stockReportCategoryFilter">
                <option value="">All Categories</option>
                <option value="Donut">Donut</option>
                <option value="Beverage">Beverage</option>
                <option value="Pastry">Pastry</option>
            </select>
            <button class="st-btn st-btn-secondary" onclick="exportCSV()">📥 Export CSV</button>
            <button class="st-btn st-btn-secondary" onclick="printReport()">🖨️ Print</button>
        </div>

        <div class="st-section-box">
            <table class="st-data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Current Stock</th>
                        <th>Minimum Stock</th>
                        <th>Stock Status</th>
                    </tr>
                </thead>
                <tbody id="stockReportBody">
                    <tr>
                        <td>1</td>
                        <td>DT-001</td>
                        <td>Sugar Donut</td>
                        <td>Regular Donuts</td>
                        <td>25</td>
                        <td>10</td>
                        <td><span class="st-badge safe">Safe Stock</span></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>DT-002</td>
                        <td>Chocolate Donut</td>
                        <td>Regular Donuts</td>
                        <td>18</td>
                        <td>10</td>
                        <td><span class="st-badge safe">Safe Stock</span></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>DT-003</td>
                        <td>Cheese Donut</td>
                        <td>Regular Donuts</td>
                        <td>5</td>
                        <td>10</td>
                        <td><span class="st-badge low">Low Stock</span></td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>BOM-001</td>
                        <td>Chocolate Bomboloni</td>
                        <td>Bomboloni</td>
                        <td>12</td>
                        <td>8</td>
                        <td><span class="st-badge safe">Safe Stock</span></td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>BEV-001</td>
                        <td>Iced Tea</td>
                        <td>Cold Drinks</td>
                        <td>8</td>
                        <td>15</td>
                        <td><span class="st-badge low">Low Stock</span></td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>BEV-002</td>
                        <td>Coffee Milk</td>
                        <td>Coffee Drinks</td>
                        <td>15</td>
                        <td>12</td>
                        <td><span class="st-badge safe">Safe Stock</span></td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td>BEV-003</td>
                        <td>Thai Tea</td>
                        <td>Cold Drinks</td>
                        <td>3</td>
                        <td>10</td>
                        <td><span class="st-badge low">Low Stock</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<!-- Profit Report Tab -->
<?php if ($can_profit): ?>
    <div id="profit-report" class="st-tab-content" <?= ($requests['tab'] == 'profit') ? 'active' : ''; ?>>
        <div class="st-filters-bar">
            <input type="month" class="st-input-field">
            <select class="st-input-field">
                <option value="">All Products</option>
            </select>
            <button class="st-btn st-btn-secondary" onclick="exportCSV()">📥 Export CSV</button>
            <button class="st-btn st-btn-secondary" onclick="printReport()">🖨️ Print</button>
        </div>

        <div class="st-section-box">
            <table class="st-data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Product Name</th>
                        <th>Total Units Sold</th>
                        <th>Total Sales Revenue</th>
                        <th>Total COGS</th>
                        <th>Gross Profit</th>
                        <th>Profit Margin</th>
                    </tr>
                </thead>
                <tbody id="profitReportBody">
                    <tr>
                        <td>1</td>
                        <td>Sugar Donut</td>
                        <td>2</td>
                        <td>Rp 50.000</td>
                        <td>Rp 20.000</td>
                        <td>Rp 30.000</td>
                        <td>60.0%</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Iced Tea</td>
                        <td>1</td>
                        <td>Rp 18.000</td>
                        <td>Rp 6.000</td>
                        <td>Rp 12.000</td>
                        <td>66.7%</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Coffee Milk</td>
                        <td>2</td>
                        <td>Rp 44.000</td>
                        <td>Rp 16.000</td>
                        <td>Rp 28.000</td>
                        <td>63.6%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection('content') ?>

<?= $this->section('script') ?>
<script>
    setDataTables('#salesReportTable');
</script>
<?= $this->endSection() ?>