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

                $can_create = auth()->user()->can('products.create');
                $can_edit = auth()->user()->can('products.edit');
                $can_delete = auth()->user()->can('products.delete');

                ?>
                <?php if ($can_create): ?>

                    <a href="<?= base_url($link . '/new'); ?>" class="btn btn-primary btn-sm mb-2"><?= temp_lang('app.new'); ?></a>

                <?php endif; ?>
                <div class="card">
                    <div class="card-body table-responsive">
                        <table class="table w-100" id="table2">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?= temp_lang('categories.category'); ?></th>
                                    <th><?= temp_lang('products.name'); ?></th>
                                    <th><?= temp_lang('products.price'); ?></th>
                                    <th><?= temp_lang('products.qty'); ?></th>
                                    <th>Hold</th>
                                    <th><?= temp_lang('products.image'); ?></th>
                                    <th><?= temp_lang('products.description'); ?></th>
                                    <th><?= temp_lang('products.active'); ?></th>
                                    <th><?= temp_lang('app.action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $a = 1;
                                foreach ($products as $product): ?>
                                    <tr>
                                        <td><?= $a++; ?></td>
                                        <td><?= esc($product->category_name); ?></td>
                                        <td><?= esc($product->name); ?></td>
                                        <td><?= esc($product->price); ?></td>
                                        <td><?= esc($product->qty); ?></td>
                                        <td><?= esc($product->hold_qty ?? 0); ?></td>
                                        <td>
                                            <img width="100px" src="<?= asset_url(); ?>uploads/products/<?= esc($product->image); ?>" alt="" srcset="">
                                        </td>
                                        <td><?= esc($product->description); ?></td>
                                        <td>
                                            <?php if ($can_edit): ?>
                                                <?php if (esc($product->is_active)) : ?>
                                                    <a class="btn btn-success btn-sm" href="<?= base_url($link . '/' . esc($product->id) . '/deactivate'); ?>">
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                <?php else : ?>
                                                    <a class="btn btn-danger btn-sm" href="<?= base_url($link . '/' . esc($product->id) . '/activate'); ?>">
                                                        <i class="fas fa-times"></i>
                                                    </a>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <?php if (esc($product->is_active)) : ?>
                                                    <a class="btn btn-success btn-sm" href="#">
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                <?php else : ?>
                                                    <a class="btn btn-danger btn-sm" href="#">
                                                        <i class="fas fa-times"></i>
                                                    </a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($can_edit): ?>
                                                <a class="btn btn-warning btn-sm mb-2" href="<?= base_url($link . '/' . esc($product->id) . '/edit'); ?>"><i class="fas fa-edit"></i></a>

                                            <?php endif; ?>
                                            <?php if ($can_delete): ?>

                                                <form class="d-inline" action='<?= base_url($link . '/' . esc($product->id)); ?>' method='post' enctype='multipart/form-data'>
                                                    <?= csrf_field(); ?>
                                                    <input type='hidden' name='_method' value='DELETE' />
                                                    <!-- GET, POST, PUT, PATCH, DELETE-->
                                                    <button type='button' data-ket="<?= temp_lang('products.delete_confirm'); ?>" onclick='confirmDelete(this)' class='btn btn-sm mb-2 btn-danger'><i class="fas fa-trash"></i></button>
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
<?= $this->endSection('content') ?>