<?= $this->extend('template/index') ?>

<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">New Menu</h1>
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
                                <label for="parent_id">Parent Menu</label>
                                <select type="text" class="form-control <?= ($error = validation_show_error('parent_id')) ? 'border-danger' : ((old('parent_id')) ? 'border-success' : ''); ?> " value="<?= old('parent_id'); ?>" id="parent_id" name="parent_id">
                                    <option value="">== BASE ==</option>
                                    <?php foreach ($menus as $menu): ?>
                                        <?php if (old('parent_id')): ?>
                                            <?php if (old('parent_id') == $menu['id']): ?>
                                                <option selected value="<?= $menu['id']; ?>"><?= $menu->title; ?></option>
                                            <?php else: ?>
                                                <option value="<?= $menu['id']; ?>"><?= $menu['title']; ?></option>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <option value="<?= $menu['id']; ?>"><?= $menu['title']; ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?= ($error) ? '<div class="error text-danger mb-2" style="margin-top: -15px">' . $error . '</div>' : ''; ?>
                            <?= (old('parent_id')) ? '<div class="error text-success mb-2" style="margin-top: -15px">Looks good!</div>' : ''; ?>

                            <div class="form-group">
                                <label for="title">Title <small class="fw-weight-bold text-danger"><b>*</b></small></label>
                                <input type="text" class="form-control <?= ($error = validation_show_error('title')) ? 'border-danger' : ((old('title')) ? 'border-success' : ''); ?>" id="title" name="title" placeholder="title" value="<?= old('title'); ?>">
                            </div>
                            <?= ($error) ? '<div class="error text-danger mb-2" style="margin-top: -15px">' . $error . '</div>' : ''; ?>
                            <?= (old('title')) ? '<div class="error text-success mb-2" style="margin-top: -15px">Looks good!</div>' : ''; ?>

                            <div class="form-group">
                                <label for="icon">Icon</label>
                                <input type="text" class="form-control <?= ($error = validation_show_error('icon')) ? 'border-danger' : ((old('icon')) ? 'border-success' : ''); ?>" id="icon" name="icon" placeholder="Ex : fa-solid fa-list or blank" value="<?= old('icon'); ?>">
                            </div>
                            <?= ($error) ? '<div class="error text-danger mb-2" style="margin-top: -15px">' . $error . '</div>' : ''; ?>
                            <?= (old('icon')) ? '<div class="error text-success mb-2" style="margin-top: -15px">Looks good!</div>' : ''; ?>

                            <div class="form-group">
                                <label for="route">Route</label>
                                <input type="text" class="form-control <?= ($error = validation_show_error('route')) ? 'border-danger' : ((old('route')) ? 'border-success' : ''); ?>" id="route" name="route" placeholder="Ex : superadmin/users or #" value="<?= old('route'); ?>">
                            </div>
                            <?= ($error) ? '<div class="error text-danger mb-2" style="margin-top: -15px">' . $error . '</div>' : ''; ?>
                            <?= (old('route')) ? '<div class="error text-success mb-2" style="margin-top: -15px">Looks good!</div>' : ''; ?>

                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-4">
                    <div class="card">
                        <div class="card-body">

                            <div class="form-group">
                                <label for="order">Order</label>
                                <input type="text" class="form-control <?= ($error = validation_show_error('order')) ? 'border-danger' : ((old('order')) ? 'border-success' : ''); ?>" id="order" name="order" placeholder="order" value="<?= old('order', 0); ?>">
                            </div>
                            <?= ($error) ? '<div class="error text-danger mb-2" style="margin-top: -15px">' . $error . '</div>' : ''; ?>
                            <?= (old('order')) ? '<div class="error text-success mb-2" style="margin-top: -15px">Looks good!</div>' : ''; ?>

                            <div class="form-group">
                                <label for="permission">Permission <small class="fw-bold text-info">(Use <b>users.access</b> example.)</small></label>
                                <input list="permissions" type="text" class="form-control <?= ($error = validation_show_error('permission')) ? 'border-danger' : ((old('permission')) ? 'border-success' : ''); ?> " value="<?= old('permission'); ?>" id="permission" name="permission">
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