<?= $this->extend('template/index') ?>

<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">New Auth Group User</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard'); ?>">Home</a></li>
                    <li class="breadcrumb-item">Data <?= $title; ?></li>
                    <li class="breadcrumb-item active">New</li>
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
        <form action="<?= base_url($link); ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field(); ?>
            <div class="row">
                <div class="col-12 col-xl-4">
                    <div class="card">
                        <div class="card-body">


                            <div class="form-group">
                                <label for="user_id">User</label>
                                <select type="text" class="form-control <?= ($error = validation_show_error('user_id')) ? 'border-danger' : ((old('user_id')) ? 'border-success' : ''); ?> " value="<?= old('user_id'); ?>" id="user_id" name="user_id">
                                    <?php foreach ($users as $user): ?>
                                        <?php if (old('user_id')): ?>
                                            <?php if (old('user_id') == $user->id): ?>
                                                <option selected value="<?= $user->id; ?>"><?= $user->username; ?></option>
                                            <?php else: ?>
                                                <option value="<?= $user->id; ?>"><?= $user->username; ?></option>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <option value="<?= $user->id; ?>"><?= $user->username; ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?= ($error) ? '<div class="error text-danger mb-2" style="margin-top: -15px">' . $error . '</div>' : ''; ?>
                            <?= (old('user_id')) ? '<div class="error text-success mb-2" style="margin-top: -15px">Looks good!</div>' : ''; ?>

                            <div class="form-group">
                                <label for="group">Group</label>
                                <select type="text" class="form-control <?= ($error = validation_show_error('group')) ? 'border-danger' : ((old('group')) ? 'border-success' : ''); ?> " value="<?= old('group'); ?>" id="group" name="group">
                                    <?php foreach ($groups as $group): ?>
                                        <?php if (old('group')): ?>
                                            <?php if (old('group') == $group->name): ?>
                                                <option selected value="<?= $group->name; ?>"><?= $group->title; ?></option>
                                            <?php else: ?>
                                                <option value="<?= $group->name; ?>"><?= $group->title; ?></option>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <option value="<?= $group->name; ?>"><?= $group->title; ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?= ($error) ? '<div class="error text-danger mb-2" style="margin-top: -15px">' . $error . '</div>' : ''; ?>
                            <?= (old('group')) ? '<div class="error text-success mb-2" style="margin-top: -15px">Looks good!</div>' : ''; ?>


                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="<?= base_url($link); ?>" class="btn btn-secondary">Cancel</a>

                        </div>
                    </div>
                </div>

            </div>
        </form>


    </div>
</section>
<?= $this->endSection('content') ?>