<?= $this->extend('template/index') ?>

<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Data <?= esc($title); ?></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard'); ?>">Home</a></li>
                    <li class="breadcrumb-item">Data <?= esc($title); ?></li>
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
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-12">
                <?php

                $can_create = auth()->user()->can('transactions.create');
                $can_edit = auth()->user()->can('transactions.edit');
                $can_delete = auth()->user()->can('transactions.delete');

                ?>
                <?php if ($can_create): ?>
                    <a href="<?= base_url($link . '/new'); ?>" class="btn btn-primary btn-sm mb-2"><?= temp_lang('app.new'); ?></a>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body table-responsive">
                        <table class="table w-100" id="table2">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?= temp_lang('customers.customer'); ?></th>
                                    <th><?= temp_lang('transactions.order_date'); ?></th>
                                    <th><?= temp_lang('transactions.schedule_date'); ?></th>
                                    <th><?= temp_lang('transactions.delivery_date'); ?></th>
                                    <th><?= temp_lang('transactions.status'); ?></th>
                                    <th><?= temp_lang('transactions.payment_status'); ?></th>
                                    <th><?= temp_lang('transactions.total_amount'); ?></th>
                                    <th><?= temp_lang('transactions.paid_amount'); ?></th>
                                    <th><?= temp_lang('transactions.refund_amount'); ?></th>
                                    <th><?= temp_lang('app.action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $a = 1;
                                foreach ($transactions as $transaction): ?>
                                    <tr>
                                        <td><?= $a++; ?></td>
                                        <td><?= esc($transaction->customer_name); ?></td>
                                        <td><?= date('d M Y', strtotime(esc($transaction->order_date))); ?></td>
                                        <td><?= $transaction->schedule_date ? date('d M Y', strtotime(esc($transaction->schedule_date))) : '-'; ?></td>
                                        <td><?= $transaction->delivery_date ? date('d M Y', strtotime(esc($transaction->delivery_date))) : '-'; ?></td>
                                        <td>
                                            <?php
                                            // Handle status badges
                                            $status = esc($transaction->status);
                                            $badgeClass = 'badge-secondary';
                                            if ($status == 'pending') $badgeClass = 'badge-warning';
                                            if ($status == 'waiting_payment') $badgeClass = 'badge-info';
                                            if ($status == 'paid') $badgeClass = 'badge-success';
                                            if ($status == 'processing') $badgeClass = 'badge-primary';
                                            if ($status == 'delivered') $badgeClass = 'badge-success';
                                            if ($status == 'cancelled') $badgeClass = 'badge-danger';
                                            ?>
                                            <span class="badge <?= $badgeClass ?>"><?= ucfirst(str_replace('_', ' ', $status)); ?></span>
                                        </td>
                                        <td>
                                            <?php
                                            // Handle payment status badges
                                            $pStatus = esc($transaction->payment_status);
                                            $pBadge = 'badge-secondary';
                                            if ($pStatus == 'unpaid') $pBadge = 'badge-danger';
                                            if ($pStatus == 'partial') $pBadge = 'badge-warning';
                                            if ($pStatus == 'paid') $pBadge = 'badge-success';
                                            if ($pStatus == 'refunded') $pBadge = 'badge-info';
                                            ?>
                                            <span class="badge <?= $pBadge ?>"><?= ucfirst($pStatus); ?></span>
                                        </td>
                                        <td><?= number_format(esc($transaction->total_amount), 2); ?></td>
                                        <td><?= number_format(esc($transaction->paid_amount), 2); ?></td>
                                        <td><?= number_format(esc($transaction->refund_amount), 2); ?></td>

                                        <td>
                                            <a class="btn btn-info btn-sm mb-2" href="<?= base_url($link . '/' . esc($transaction->id)); ?>"><i class="fas fa-eye"></i></a>

                                            <?php if ($can_edit): ?>
                                                <a class="btn btn-warning btn-sm mb-2" href="<?= base_url($link . '/' . esc($transaction->id) . '/edit'); ?>"><i class="fas fa-edit"></i></a>
                                            <?php endif; ?>

                                            <?php if ($can_delete): ?>
                                                <form class="d-inline" action='<?= base_url($link . '/' . esc($transaction->id)); ?>' method='post'>
                                                    <?= csrf_field(); ?>
                                                    <input type='hidden' name='_method' value='DELETE' />
                                                    <button type='button' data-ket="Are you sure you want to delete this transaction?" onclick='confirmDelete(this)' class='btn btn-sm mb-2 btn-danger'><i class="fas fa-trash"></i></button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
<?= $this->endSection('content') ?>