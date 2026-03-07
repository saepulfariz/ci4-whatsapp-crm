<?= $this->extend('template/index') ?>

<?= $this->section('content') ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">New <?= $title; ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard'); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('broadcasts'); ?>"><?= temp_lang('broadcasts.broadcasts'); ?></a></li>
                    <li class="breadcrumb-item active">New <?= temp_lang('broadcasts.variables'); ?></li>
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
                    <form action="<?= base_url($link); ?>" method="post">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="broadcast_id" value="<?= esc($broadcast_id); ?>">
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="name" class="col-sm-2 col-form-label"><?= temp_lang('broadcasts.name'); ?></label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">{</span>
                                        </div>
                                        <input type="text" class="form-control" name="name" id="name" required value="<?= old('name'); ?>">
                                        <div class="input-group-append">
                                            <span class="input-group-text">}</span>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Enter the variable identifier without brackets. For example: `client_name`.</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="<?= base_url($link . '?broadcast_id=' . esc($broadcast_id)); ?>" class="btn btn-secondary"><?= temp_lang('app.cancel'); ?></a>
                            <button type="submit" class="btn btn-primary"><?= temp_lang('app.save'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
