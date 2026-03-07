<?= $this->extend('template/index') ?>

<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit Auth Group</h1>
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
        <form action="<?= base_url($link . '/' . $group->id); ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field(); ?>
            <input type='hidden' name='_method' value='PUT' />
            <div class="row">
                <div class="col-12 col-xl-4">
                    <div class="card">
                        <div class="card-body">

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control <?= ($error = validation_show_error('name')) ? 'border-danger' : ((old('name')) ? 'border-success' : ''); ?>" id="name" name="name" placeholder="Name" value="<?= old('name', $group->name); ?>">
                            </div>
                            <?= ($error) ? '<div class="error text-danger mb-2" style="margin-top: -15px">' . $error . '</div>' : ''; ?>
                            <?= (old('name')) ? '<div class="error text-success mb-2" style="margin-top: -15px">Looks good!</div>' : ''; ?>

                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control <?= ($error = validation_show_error('title')) ? 'border-danger' : ((old('title')) ? 'border-success' : ''); ?>" id="title" name="title" placeholder="Title" value="<?= old('title', $group->title); ?>">
                            </div>
                            <?= ($error) ? '<div class="error text-danger mb-2" style="margin-top: -15px">' . $error . '</div>' : ''; ?>
                            <?= (old('title')) ? '<div class="error text-success mb-2" style="margin-top: -15px">Looks good!</div>' : ''; ?>



                            <div class="form-group">
                                <label for="description">description</label>
                                <textarea class="form-control <?= ($error = validation_show_error('description')) ? 'border-danger' : ((old('description')) ? 'border-success' : ''); ?>" name="description" id="description"><?= old('description', $group->description); ?></textarea>
                            </div>
                            <?= ($error) ? '<div class="error text-danger mb-2" style="margin-top: -15px">' . $error . '</div>' : ''; ?>
                            <?= (old('description')) ? '<div class="error text-success mb-2" style="margin-top: -15px">Looks good!</div>' : ''; ?>

                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="<?= base_url($link); ?>" class="btn btn-secondary">Cancel</a>

                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-8">

                    <div class=" table-wrapper table-responsive">
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                            <?php foreach ($permissions as $group => $items): ?>
                                <div class="col">
                                    <div class=" rounded-3 p-4 mb-4 card card-body">
                                        <h2 class="h5 text-dark mb-4 border-bottom pb-2"><?= ucfirst($group) ?></h2>
                                        <div class="space-y-2">
                                            <?php foreach ($items as $permission): ?>
                                                <?php
                                                $oldPermissions = old('permissions') ?? [];
                                                $rolePermissions = $rolePermissions ?? [];
                                                $checked = in_array($permission->name, !empty($oldPermissions) ? $oldPermissions : $rolePermissions) ? 'checked' : '';
                                                ?>
                                                <div class="d-flex align-items-center form-check">
                                                    <input type="checkbox" name="permissions[]" value="<?= $permission->name ?>" id="check-<?= $permission->id ?>" class="form-check-input rounded border-gray-300 text-primary" <?= $checked ?>>
                                                    <label for="check-<?= $permission->id ?>" class="ms-2 text-dark form-check-label">
                                                        <?= htmlspecialchars($permission->name) ?>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </div>
            </div>
        </form>


    </div>
</section>
<?= $this->endSection('content') ?>