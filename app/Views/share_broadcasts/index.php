<?= $this->extend('template/index') ?>

<?= $this->section('content') ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Data <?= $title; ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard'); ?>">Home</a></li>
                    <li class="breadcrumb-item active"><?= $title; ?></li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <?php
                $can_create = auth()->user()->can('share-broadcasts.create');
                $can_reshare = auth()->user()->can('share-broadcasts.reshare');
                ?>
                <?php if ($can_create): ?>
                    <a href="<?= base_url($link . '/new'); ?>" class="btn btn-primary btn-sm mb-2"><?= temp_lang('broadcasts.compose'); ?></a>
                <?php endif; ?>
                <div class="card">
                    <div class="card-body table-responsive">
                        <table class="table w-100" id="table2">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Template</th>
                                    <th><?= temp_lang('broadcasts.recipient'); ?></th>
                                    <th><?= temp_lang('broadcasts.preview'); ?></th>
                                    <th><?= temp_lang('broadcasts.status'); ?></th>
                                    <th><?= temp_lang('broadcasts.created_at'); ?></th>
                                    <th><?= temp_lang('app.action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $a = 1;
                                foreach ($logs as $log): ?>
                                    <tr>
                                        <td><?= $a++; ?></td>
                                        <td><?= esc($log->template_title); ?></td>

                                        <td>
                                            <?= $log->customer_name ? esc($log->customer_name) : '<em>Custom</em>'; ?>
                                            <br>
                                            <small class="text-muted"><?= esc($log->to); ?></small>
                                        </td>
                                        <td>
                                            <div style="max-height: 100px; overflow-y: auto; white-space: pre-wrap; font-size: 0.85rem; background: #f8f9fa; padding: 5px; border-radius: 4px;"><?= esc($log->content); ?></div>
                                        </td>
                                        <td>
                                            <?php if ($log->status == 'pending'): ?>
                                                <span class="badge badge-warning">Pending</span>
                                            <?php elseif ($log->status == 'sent'): ?>
                                                <span class="badge badge-success">Sent</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger"><?= esc($log->status); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= esc($log->created_at); ?></td>
                                        <td>
                                            <?php if ($can_reshare): ?>
                                                <a href="<?= base_url($link . '/reshare/' . $log->id); ?>" class="btn btn-info btn-xs" title="<?= temp_lang('broadcasts.reshare'); ?>">
                                                    <i class="fas fa-sync-alt"></i>
                                                </a>
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
<?= $this->endSection() ?>