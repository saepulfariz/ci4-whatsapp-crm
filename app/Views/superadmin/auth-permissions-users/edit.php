<?= $this->extend('template/index') ?>

<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit Auth Permission User</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard'); ?>">Home</a></li>
                    <li class="breadcrumb-item">Data <?= $title; ?></li>
                    <li class="breadcrumb-item active">Edit</li>
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
        <form action="<?= base_url($link . '/' . $permission_user['id']); ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field(); ?>
            <input type='hidden' name='_method' value='PUT' />
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
                                            <?php if ($permission_user['user_id'] == $user->id): ?>
                                                <option selected value="<?= $user->id; ?>"><?= $user->username; ?></option>
                                            <?php else: ?>
                                                <option value="<?= $user->id; ?>"><?= $user->username; ?></option>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?= ($error) ? '<div class="error text-danger mb-2" style="margin-top: -15px">' . $error . '</div>' : ''; ?>
                            <?= (old('user_id')) ? '<div class="error text-success mb-2" style="margin-top: -15px">Looks good!</div>' : ''; ?>


                            <div class="form-group">
                                <label for="permission">Permission <small class="fw-bold text-info">(Use <b>users.*</b> to access all)</small></label>
                                <input list="permissions" type="text" class="form-control <?= ($error = validation_show_error('permission')) ? 'border-danger' : ((old('permission')) ? 'border-success' : ''); ?> " value="<?= old('permission', $permission_user['permission']); ?>" id="permission" name="permission">
                            </div>
                            <?= ($error) ? '<div class="error text-danger mb-2" style="margin-top: -15px">' . $error . '</div>' : ''; ?>
                            <?= (old('permission')) ? '<div class="error text-success mb-2" style="margin-top: -15px">Looks good!</div>' : ''; ?>
                            <datalist id="permissions">
                                <?php foreach ($permissions as $permission) : ?>
                                    <option value="<?= $permission->name; ?>"><?= $permission->name; ?></option>
                                <?php endforeach; ?>
                            </datalist>

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