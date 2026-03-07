<?= $this->extend('template/index') ?>

<?= $this->section('content') ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit <?= $title; ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard'); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url($link); ?>"><?= $title; ?></a></li>
                    <li class="breadcrumb-item active">Edit <?= $title; ?></li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 mb-2">
                <div class="card">
                    <form action="<?= base_url($link . '/' . $broadcast->id); ?>" method="post">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="_method" value="PUT">
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="title" class="col-sm-2 col-form-label"><?= temp_lang('broadcasts.title'); ?></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="title" id="title" required value="<?= old('title', $broadcast->title); ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="content" class="col-sm-2 col-form-label"><?= temp_lang('broadcasts.content'); ?></label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="content" id="content" rows="6" required><?= old('content', $broadcast->content); ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="<?= base_url($link); ?>" class="btn btn-secondary"><?= temp_lang('app.cancel'); ?></a>
                            <button type="submit" class="btn btn-primary"><?= temp_lang('app.save'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <!-- list broadcast variables get by database -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?= temp_lang('broadcasts.variables'); ?></h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <?php foreach ($variables as $variable) : ?>
                                <li class="list-group-item"><?= $variable->name; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>