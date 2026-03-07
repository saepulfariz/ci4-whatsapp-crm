<?= $this->extend('template/index') ?>

<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?= esc($title); ?></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard'); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url($link); ?>">Transactions</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div>
            <!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12 col-xl-4">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="card-title text-light"><?= temp_lang('transactions.customer_info'); ?></h3>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-4"><?= temp_lang('customers.customer'); ?></dt>
                            <dd class="col-sm-8"><?= esc($transaction->customer_name); ?></dd>

                            <dt class="col-sm-4"><?= temp_lang('customers.phone'); ?></dt>
                            <dd class="col-sm-8"><?= esc($transaction->phone); ?></dd>

                            <dt class="col-sm-4"><?= temp_lang('customers.address'); ?></dt>
                            <dd class="col-sm-8"><?= esc($transaction->address); ?></dd>

                            <hr class="w-100 my-2">

                            <dt class="col-sm-4"><?= temp_lang('transactions.order_date'); ?></dt>
                            <dd class="col-sm-8"><?= date('d M Y', strtotime(esc($transaction->order_date))); ?></dd>

                            <dt class="col-sm-4"><?= temp_lang('transactions.schedule_date'); ?></dt>
                            <dd class="col-sm-8"><?= $transaction->schedule_date ? date('d M Y', strtotime(esc($transaction->schedule_date))) : '-'; ?></dd>

                            <dt class="col-sm-4"><?= temp_lang('transactions.delivery_date'); ?></dt>
                            <dd class="col-sm-8"><?= $transaction->delivery_date ? date('d M Y', strtotime(esc($transaction->delivery_date))) : '-'; ?></dd>

                            <dt class="col-sm-4"><?= temp_lang('transactions.status'); ?></dt>
                            <dd class="col-sm-8">
                                <span class="badge badge-secondary"><?= ucfirst(str_replace('_', ' ', esc($transaction->status))); ?></span>
                            </dd>

                            <dt class="col-sm-4"><?= temp_lang('transactions.payment_status'); ?></dt>
                            <dd class="col-sm-8">
                                <span class="badge badge-secondary"><?= ucfirst(esc($transaction->payment_status)); ?></span>
                            </dd>

                            <?php if (!empty($transaction->note)): ?>
                                <dt class="col-sm-4"><?= temp_lang('transactions.note'); ?></dt>
                                <dd class="col-sm-8"><?= esc($transaction->note); ?></dd>
                            <?php endif; ?>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-8">
                <div class="card">
                    <div class="card-header bg-secondary">
                        <h3 class="card-title text-light"><?= temp_lang('transactions.order_summary'); ?></h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?= temp_lang('products.product'); ?></th>
                                    <th><?= temp_lang('products.price'); ?></th>
                                    <th><?= temp_lang('products.qty'); ?></th>
                                    <th><?= temp_lang('transactions.discount_total'); ?></th>
                                    <th><?= temp_lang('transactions.subtotal_price'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $a = 1;
                                foreach ($details as $detail): ?>
                                    <tr>
                                        <td><?= $a++; ?></td>
                                        <td><?= esc($detail->product_name); ?></td>
                                        <td><?= number_format(esc($detail->price), 2); ?></td>
                                        <td><?= esc($detail->qty); ?></td>
                                        <td><?= number_format(esc($detail->discount_amount), 2); ?></td>
                                        <td><?= number_format(esc($detail->total_price), 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-right"><?= temp_lang('transactions.subtotal_price'); ?>:</th>
                                    <th><?= number_format(esc($transaction->subtotal_price), 2); ?></th>
                                </tr>
                                <tr>
                                    <th colspan="5" class="text-right"><?= temp_lang('transactions.discount_total'); ?>:</th>
                                    <th>- <?= number_format(esc($transaction->discount_total), 2); ?></th>
                                </tr>
                                <tr>
                                    <th colspan="5" class="text-right"><?= temp_lang('transactions.tax_total'); ?>:</th>
                                    <th>+ <?= number_format(esc($transaction->tax_total), 2); ?></th>
                                </tr>
                                <tr class="bg-light">
                                    <th colspan="5" class="text-right"><?= temp_lang('transactions.total_amount'); ?>:</th>
                                    <th class="text-primary font-weight-bold"><?= number_format(esc($transaction->total_amount), 2); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="card-footer">
                        <a href="<?= base_url($link); ?>" class="btn btn-secondary"><?= temp_lang('app.back'); ?></a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
<?= $this->endSection('content') ?>