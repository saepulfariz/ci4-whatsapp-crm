<?= $this->extend('template/index') ?>

<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?= temp_lang('app.edit'); ?> <?= temp_lang('customers.customer'); ?></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard'); ?>">Home</a></li>
                    <li class="breadcrumb-item">Data <?= $title; ?></li>
                    <li class="breadcrumb-item active"><?= temp_lang('app.edit'); ?></li>
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
        <form action="<?= base_url($link . '/' . esc($customer->id)); ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field(); ?>
            <input type='hidden' name='_method' value='PUT' />
            <div class="row">
                <div class="col-12 col-xl-4">
                    <div class="card">
                        <div class="card-body">

                            <div class="form-group">
                                <label for="name"><?= temp_lang('customers.name'); ?> <small class="fw-weight-bold text-danger"><b>*</b></small></label>
                                <input type="text" class="form-control <?= ($error = validation_show_error('name')) ? 'border-danger' : ((old('name')) ? 'border-success' : ''); ?>" id="name" name="name" placeholder="name" value="<?= old('name', esc($customer->name)); ?>">
                            </div>
                            <?= ($error) ? '<div class="error text-danger mb-2" style="margin-top: -15px">' . $error . '</div>' : ''; ?>
                            <?= (old('name')) ? '<div class="error text-success mb-2" style="margin-top: -15px">Looks good!</div>' : ''; ?>


                            <div class="form-group">
                                <label for="phone"><?= temp_lang('customers.phone'); ?> (0812345678)</label>
                                <input type="text" class="form-control <?= ($error = validation_show_error('phone')) ? 'border-danger' : ((old('phone')) ? 'border-success' : ''); ?>" id="phone" name="phone" placeholder="phone" value="<?= old('phone', esc($customer->phone)); ?>">
                            </div>
                            <?= ($error) ? '<div class="error text-danger mb-2" style="margin-top: -15px">' . $error . '</div>' : ''; ?>
                            <?= (old('phone')) ? '<div class="error text-success mb-2" style="margin-top: -15px">Looks good!</div>' : ''; ?>


                            <div class="form-group">
                                <label for="address"><?= temp_lang('customers.address'); ?></label>
                                <textarea class="form-control <?= ($error = validation_show_error('address')) ? 'border-danger' : ((old('address')) ? 'border-success' : ''); ?>" id="address" name="address" placeholder="address"><?= old('address', $customer->address); ?></textarea>
                            </div>
                            <?= ($error) ? '<div class="error text-danger mb-2" style="margin-top: -15px">' . $error . '</div>' : ''; ?>
                            <?= (old('address')) ? '<div class="error text-success mb-2" style="margin-top: -15px">Looks good!</div>' : ''; ?>

                            <button type="submit" class="btn btn-primary"><?= temp_lang('app.update'); ?></button>
                            <a href="<?= base_url($link); ?>" class="btn btn-secondary"><?= temp_lang('app.cancel'); ?></a>

                        </div>
                    </div>
                </div>

            </div>
        </form>


    </div>
</section>
<?= $this->endSection('content') ?>