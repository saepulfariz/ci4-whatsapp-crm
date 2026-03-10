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
                <div class="card">
                    <div class="card-body table-responsive">
                        <table class="table w-100" id="table2">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?= temp_lang('chat_bots.from'); ?></th>
                                    <th><?= temp_lang('chat_bots.name'); ?></th>
                                    <th><?= temp_lang('chat_bots.question'); ?></th>
                                    <th><?= temp_lang('chat_bots.answer'); ?></th>
                                    <th><?= temp_lang('chat_bots.timestamp'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $a = 1;
                                foreach ($data as $item): ?>
                                    <tr>
                                        <td><?= $a++; ?></td>
                                        <td><?= esc($item->from); ?></td>
                                        <td><?= esc($item->name); ?></td>
                                        <td><?= esc($item->question); ?></td>
                                        <td><?= esc($item->answer); ?></td>
                                        <td><?= esc($item->created_at); ?></td>
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