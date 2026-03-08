<?= $this->extend('template/index') ?>

<?= $this->section('content') ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?= $title; ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard'); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url($link); ?>"><?= temp_lang('auto_replies.title'); ?></a></li>
                    <li class="breadcrumb-item active"><?= $title; ?></li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card card-primary card-outline">
                    <form action="<?= base_url($link . '/' . $auto_reply->id); ?>" method="post">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="_method" value="PUT">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="keyword"><?= temp_lang('auto_replies.keyword'); ?></label>
                                <input type="text" name="keyword" id="keyword" class="form-control" value="<?= old('keyword', $auto_reply->keyword); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="content"><?= temp_lang('auto_replies.response'); ?></label>
                                <textarea name="content" id="content" class="form-control" rows="4" required><?= old('content', $auto_reply->content); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label><?= temp_lang('auto_replies.match_type'); ?></label>
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="match1" name="is_exact_match" value="1" <?= $auto_reply->is_exact_match ? 'checked' : ''; ?>>
                                    <label for="match1" class="custom-control-label"><?= temp_lang('auto_replies.exact_match'); ?></label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="match0" name="is_exact_match" value="0" <?= !$auto_reply->is_exact_match ? 'checked' : ''; ?>>
                                    <label for="match0" class="custom-control-label"><?= temp_lang('auto_replies.contains'); ?></label>
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
        </div>
    </div>
</section>
<?= $this->endSection() ?>
