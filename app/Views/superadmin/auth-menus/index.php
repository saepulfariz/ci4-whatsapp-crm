<?= $this->extend('template/index') ?>

<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Data <?= $title; ?></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard'); ?>">Home</a></li>
                    <li class="breadcrumb-item">Data <?= $title; ?></li>
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
        <div class="row">
            <div class="col-12">
                <?php

                $can_create = auth()->user()->can('menus.create');
                $can_edit = auth()->user()->can('menus.edit');
                $can_delete = auth()->user()->can('menus.delete');

                ?>
                <a href="<?= base_url($link . '/new'); ?>" class="btn btn-primary btn-sm mb-2">New</a>
                <a href="<?= base_url($link . '/order'); ?>" class="btn btn-secondary btn-sm mb-2">Order</a>
                <div class="card">
                    <div class="card-body">
                        <table class="table" id="table2">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Parent</th>
                                    <th>Title</th>
                                    <th>Icon</th>
                                    <th>Route</th>
                                    <th>Order</th>
                                    <th>Permission</th>
                                    <th>Active</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $a = 1;
                                foreach ($menus as $menu): ?>
                                    <tr>
                                        <td><?= $a++; ?></td>
                                        <td><?= $menu['parent_title']; ?></td>
                                        <td><?= $menu['title']; ?></td>
                                        <td><?= $menu['icon']; ?></td>
                                        <td><?= $menu['route']; ?></td>
                                        <td><?= $menu['order']; ?></td>
                                        <td><?= $menu['permission']; ?></td>
                                        <td>
                                            <?php if ($menu['active']) : ?>
                                                <a class="btn btn-success btn-sm" href="<?= base_url($link . '/' . $menu['id'] . '/deactivate'); ?>">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            <?php else : ?>
                                                <a class="btn btn-danger btn-sm" href="<?= base_url($link . '/' . $menu['id'] . '/activate'); ?>">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a class="btn btn-warning btn-sm mb-2" href="<?= base_url($link . '/' . $menu['id'] . '/edit'); ?>"><i class="fas fa-edit"></i></a>
                                            <form class="d-inline" action='<?= base_url($link . '/' . $menu['id']); ?>' method='post' enctype='multipart/form-data'>
                                                <?= csrf_field(); ?>
                                                <input type='hidden' name='_method' value='DELETE' />
                                                <!-- GET, POST, PUT, PATCH, DELETE-->
                                                <button type='button' onclick='confirmDelete(this)' class='btn btn-sm mb-2 btn-danger'><i class="fas fa-trash"></i></button>
                                            </form>

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
<?= $this->endSection('content') ?>