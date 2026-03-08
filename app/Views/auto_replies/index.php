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
                $can_create = auth()->user()->can('auto-replies.create');
                $can_edit = auth()->user()->can('auto-replies.edit');
                $can_delete = auth()->user()->can('auto-replies.delete');
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
                                    <th><?= temp_lang('auto_replies.keyword'); ?></th>
                                    <th><?= temp_lang('auto_replies.response'); ?></th>
                                    <th><?= temp_lang('auto_replies.match_type'); ?></th>
                                    <th><?= temp_lang('app.action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $a = 1;
                                foreach ($data as $item): ?>
                                    <tr>
                                        <td><?= $a++; ?></td>
                                        <td><strong><?= esc($item->keyword); ?></strong></td>
                                        <td><?= esc($item->content); ?></td>
                                        <td>
                                            <?php if ($item->is_exact_match): ?>
                                                <span class="badge badge-success"><?= temp_lang('auto_replies.exact_match'); ?></span>
                                            <?php else: ?>
                                                <span class="badge badge-info"><?= temp_lang('auto_replies.contains'); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($can_edit): ?>
                                                <a href="<?= base_url($link . '/' . $item->id . '/edit'); ?>" class="btn btn-info btn-sm"><i class="fas fa-edit"></i></a>
                                            <?php endif; ?>
                                            <?php if ($can_delete): ?>
                                                <form action="<?= base_url($link . '/' . $item->id); ?>" method="post" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                                    <?= csrf_field(); ?>
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
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
<?= $this->endSection() ?>