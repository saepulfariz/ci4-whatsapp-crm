<?= $this->extend('template/index') ?>

<?= $this->section('content') ?>
<!-- Summary Cards -->
<div class="st-cards-grid">
    <div class="st-card">
        <div class="st-card-header">Today's Sales</div>
        <div class="st-card-value">Rp <?= number_format($sales['today']->total_sales, 0, ',', '.'); ?></div>
        <div class="st-card-subtitle">+<?= $sales['persen']; ?>% from yesterday</div>
    </div>
    <div class="st-card">
        <div class="st-card-header">Total Transactions</div>
        <div class="st-card-value"><?= number_format($transaction['today']->total_transaction, 0, ',', '.'); ?></div>
        <div class="st-card-subtitle">+<?= $transaction['gap']; ?> from yesterday</div>
    </div>
    <div class="st-card">
        <div class="st-card-header">Best-Selling Product</div>
        <div class="st-card-value"><?= $best_selling_product->name ?? '-'; ?></div>
        <div class="st-card-subtitle"><?= $best_selling_product->total_sold ?? '-'; ?> units sold today</div>
    </div>
    <div class="st-card">
        <div class="st-card-header">Low Stock Alert</div>
        <div class="st-card-value"><?= count($stock_low); ?></div>
        <div class="st-card-subtitle">Products below minimum</div>
    </div>
    <div class="st-card">
        <div class="st-card-header">Weekly Revenue</div>
        <div class="st-card-value">Rp <?= number_format($weekly_revenue->weekly_revenue, 0, ',', '.'); ?></div>
        <div class="st-card-subtitle">Last 7 days</div>
    </div>
    <div class="st-card">
        <div class="st-card-header">Est. Gross Profit</div>
        <div class="st-card-value">Rp <?= number_format($gross_profit->gross_profit, 0, ',', '.'); ?></div>
        <div class="st-card-subtitle"><?= number_format($gross_profit->margin, 2, ',', '.'); ?>% margin</div>
    </div>
</div>

<!-- Charts Section -->
<div class="st-charts-container">
    <div class="st-chart-box">
        <h3>Sales This Week</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="start_date_sales">Start Date</label>
                    <input type="date" class="form-control" id="start_date_sales" name="start_date_sales" value="<?= date('Y-m-d', strtotime('-6 days')); ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="end_date_sales">End Date</label>
                    <input type="date" class="form-control" id="end_date_sales" name="end_date_sales" value="<?= date('Y-m-d'); ?>">
                </div>
            </div>
        </div>
        <canvas id="salesChart"></canvas>
    </div>

    <div class="st-chart-box">
        <h3>Product Category Distribution (%)</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="start_date_category">Start Date</label>
                    <input type="date" class="form-control" id="start_date_category" name="start_date_category" value="<?= date('Y-m-d', strtotime('-6 days')); ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="end_date_category">End Date</label>
                    <input type="date" class="form-control" id="end_date_category" name="end_date_category" value="<?= date('Y-m-d'); ?>">
                </div>
            </div>
        </div>
        <canvas id="categoryChart"></canvas>
    </div>
</div>

<!-- Latest Transactions -->
<div class="st-section-box">
    <h3>Latest Transactions</h3>
    <table class="st-data-table">
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
            <?php $no = 1;
            foreach ($transaction_history as $key => $transaction) : ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= date('d/m/Y H:i:s', strtotime($transaction->created_at)); ?></td>
                    <td><strong><?= esc($transaction->code); ?></strong></td>
                    <td><?= number_format($transaction->total_items, 0); ?> items</td>
                    <td>Rp <?= number_format($transaction->total_amount, 0, ',', '.'); ?></td>
                    <td><?= esc($transaction->payment_method_name ?? '-'); ?></td>
                    <td>
                        <?php
                        $statusClass = 'pending';
                        if ($transaction->status === 'delivered' || $transaction->status === 'completed' || $transaction->status === 'paid') $statusClass = 'active';
                        if ($transaction->status === 'cancelled') $statusClass = 'inactive';
                        ?>
                        <div class="st-badge <?= $statusClass; ?>"><?= ucfirst(str_replace('_', ' ', $transaction->status)); ?></div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Low Stock Alert -->
<div class="st-section-box">
    <h3>Low Stock Alert</h3>
    <table class="st-data-table">
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
            <?php if (empty($stock_low)) : ?>
                <tr>
                    <td colspan="5" class="text-center">No low stock products</td>
                </tr>
            <?php else : ?>
                <?php $no = 1; ?>
                <?php foreach ($stock_low as $key => $product) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= esc($product->name); ?></td>
                        <td><?= number_format($product->stock, 0); ?></td>
                        <td><?= number_format($product->min_qty, 0); ?></td>
                        <td><span class="st-badge low">Low Stock</span></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection('content') ?>


<?= $this->section('script') ?>

<script>
    let salesChart; // chart instance global

    function initSalesChart() {
        const ctx = document.getElementById('salesChart').getContext('2d');
        salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [], // awalnya kosong
                datasets: [{
                    label: 'Sales',
                    data: [],
                    backgroundColor: '#FF6B6B',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    initSalesChart();

    function salesThisWeek() {
        const start_date = document.getElementById('start_date_sales').value;
        const end_date = document.getElementById('end_date_sales').value;

        fetch('<?= base_url('api/sales-week'); ?>?start_date=' + start_date + '&end_date=' + end_date)
            .then(res => res.json())
            .then(data => {
                // update chart tanpa init ulang
                salesChart.data.labels = data.data.labels;
                salesChart.data.datasets[0].data = data.data.data; // sesuaikan key API
                salesChart.update();
            });
    }
    salesThisWeek();

    let categoryChart; // instance chart global

    function initCategoryChart() {
        const ctx = document.getElementById('categoryChart').getContext('2d');
        categoryChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: [], // awalnya kosong
                datasets: [{
                    data: [],
                    backgroundColor: ['#FF6B6B', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.raw + '%';
                            }
                        }
                    }
                }
            }
        });
    }

    initCategoryChart();

    function productCategoryDistribution() {
        const start_date = document.getElementById('start_date_category').value;
        const end_date = document.getElementById('end_date_category').value;

        fetch('<?= base_url('api/product-category-distribution'); ?>?start_date=' + start_date + '&end_date=' + end_date)
            .then(res => res.json())
            .then(data => {
                // update chart data
                categoryChart.data.labels = data.labels;
                categoryChart.data.datasets[0].data = data.percentages;
                categoryChart.update();
            });
    }

    productCategoryDistribution();

    // change start_date and end_date
    document.getElementById('start_date_category').addEventListener('change', function() {
        productCategoryDistribution();
    });
    document.getElementById('end_date_category').addEventListener('change', function() {
        productCategoryDistribution();
    });

    // change start_date and end_date
    document.getElementById('start_date_sales').addEventListener('change', function() {
        salesThisWeek();
    });
    document.getElementById('end_date_sales').addEventListener('change', function() {
        salesThisWeek();
    });
</script>
<?= $this->endSection('script') ?>