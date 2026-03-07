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
                    <li class="breadcrumb-item"><a href="<?= base_url('broadcasts'); ?>"><?= temp_lang('broadcasts.broadcasts'); ?></a></li>
                    <li class="breadcrumb-item active">Data <?= temp_lang('broadcasts.variables'); ?></li>
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
                $can_create = auth()->user()->can('broadcasts.create');
                $can_edit = auth()->user()->can('broadcasts.edit');
                $can_delete = auth()->user()->can('broadcasts.delete');
                ?>
                <a href="<?= base_url('broadcasts'); ?>" class="btn btn-secondary btn-sm mb-2"><i class="fas fa-arrow-left"></i> <?= temp_lang('broadcasts.back_to_broadcasts'); ?></a>
                
                <?php if ($can_create): ?>
                    <a href="<?= base_url($link . '/new?broadcast_id=' . esc($broadcast_id)); ?>" class="btn btn-primary btn-sm mb-2 float-right"><?= temp_lang('app.new'); ?></a>
                <?php endif; ?>
                
                <div class="card">
                    <div class="card-body table-responsive">
                        <table class="table w-100" id="table2">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?= temp_lang('broadcasts.name'); ?></th>
                                    <th><?= temp_lang('app.action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $a = 1;
                                foreach ($variables as $var): ?>
                                    <tr>
                                        <td><?= $a++; ?></td>
                                        <td>{<?= esc($var->name); ?>}</td>
                                        <td style="white-space: nowrap;">
                                            <?php if ($can_edit): ?>
                                                <a class="btn btn-warning btn-sm mb-2" href="<?= base_url($link . '/' . esc($var->id) . '/edit'); ?>"><i class="fas fa-edit"></i></a>
                                            <?php endif; ?>
                                            <?php if ($can_delete): ?>
                                                <form action="<?= base_url($link . '/' . esc($var->id)); ?>" method="post" class="d-inline">
                                                    <?= csrf_field(); ?>
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit" class="btn btn-danger btn-sm mb-2" onclick="return confirm('<?= temp_lang('app.confirm'); ?>')"><i class="fas fa-trash"></i></button>
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
