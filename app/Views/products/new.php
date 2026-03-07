<?= $this->extend('template/index') ?>

<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?= temp_lang('app.new'); ?> <?= temp_lang('products.product'); ?></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard'); ?>">Home</a></li>
                    <li class="breadcrumb-item">Data <?= $title; ?></li>
                    <li class="breadcrumb-item active"><?= temp_lang('app.new'); ?></li>
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
                                <label for="category_id"><?= temp_lang('categories.category'); ?></label>
                                <select type="text" class="form-control <?= ($error = validation_show_error('category_id')) ? 'border-danger' : ((old('category_id')) ? 'border-success' : ''); ?> " value="<?= old('category_id'); ?>" id="category_id" name="category_id">
                                    <?php foreach ($categories as $category): ?>
                                        <?php if (old('category_id')): ?>
                                            <?php if (old('category_id') == $category->id): ?>
                                                <option selected value="<?= $category->id; ?>"><?= $category->name; ?></option>
                                            <?php else: ?>
                                                <option value="<?= $category->id; ?>"><?= $category->name; ?></option>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <option value="<?= $category->id; ?>"><?= $category->name; ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?= ($error) ? '<div class="error text-danger mb-2" style="margin-top: -15px">' . $error . '</div>' : ''; ?>
                            <?= (old('category_id')) ? '<div class="error text-success mb-2" style="margin-top: -15px">Looks good!</div>' : ''; ?>


                            <div class="form-group">
                                <label for="name"><?= temp_lang('products.name'); ?> <small class="fw-weight-bold text-danger"><b>*</b></small></label>
                                <input type="text" class="form-control <?= ($error = validation_show_error('name')) ? 'border-danger' : ((old('name')) ? 'border-success' : ''); ?>" id="name" name="name" placeholder="Name" value="<?= old('name'); ?>">
                            </div>
                            <?= ($error) ? '<div class="error text-danger mb-2" style="margin-top: -15px">' . $error . '</div>' : ''; ?>
                            <?= (old('name')) ? '<div class="error text-success mb-2" style="margin-top: -15px">Looks good!</div>' : ''; ?>

                            <div class="form-group">
                                <label for="price"><?= temp_lang('products.price'); ?></label>
                                <input type="number" step="0.1" class="form-control <?= ($error = validation_show_error('price')) ? 'border-danger' : ((old('price')) ? 'border-success' : ''); ?>" id="price" name="price" placeholder="Price" value="<?= old('price'); ?>">
                            </div>
                            <?= ($error) ? '<div class="error text-danger mb-2" style="margin-top: -15px">' . $error . '</div>' : ''; ?>
                            <?= (old('price')) ? '<div class="error text-success mb-2" style="margin-top: -15px">Looks good!</div>' : ''; ?>

                            <div class="form-group">
                                <label for="qty"><?= temp_lang('products.qty'); ?></label>
                                <input type="number" class="form-control <?= ($error = validation_show_error('qty')) ? 'border-danger' : ((old('qty')) ? 'border-success' : ''); ?>" id="qty" name="qty" placeholder="Qty" value="<?= old('qty'); ?>">
                            </div>
                            <?= ($error) ? '<div class="error text-danger mb-2" style="margin-top: -15px">' . $error . '</div>' : ''; ?>
                            <?= (old('qty')) ? '<div class="error text-success mb-2" style="margin-top: -15px">Looks good!</div>' : ''; ?>


                            <div class="form-group">
                                <label for="image"><?= temp_lang('products.image'); ?> <small class="fw-weight-bold text-danger"><b>*</b></small></label>
                                <div id="imagePreview">
                                    <img class="img-thumbnail d-block mb-2" width="100" src="<?= asset_url(); ?>uploads/products/product.png" alt="">

                                </div>
                                <input type="file" onchange="previewImage(this, '#imagePreview')" class="form-control <?= ($error = validation_show_error('image')) ? 'border-danger' : ((old('image')) ? 'border-success' : ''); ?>" id="image" name="image">
                            </div>
                            <?= ($error) ? '<div class="error text-danger mb-2" style="margin-top: -15px">' . $error . '</div>' : ''; ?>
                            <?= (old('image')) ? '<div class="error text-success mb-2" style="margin-top: -15px">Looks good!</div>' : ''; ?>

                            <div class="form-group">
                                <label for="description"><?= temp_lang('products.description'); ?></label>
                                <textarea class="form-control <?= ($error = validation_show_error('description')) ? 'border-danger' : ((old('description')) ? 'border-success' : ''); ?>" id="description" name="description" placeholder="description"><?= old('description'); ?></textarea>
                            </div>
                            <?= ($error) ? '<div class="error text-danger mb-2" style="margin-top: -15px">' . $error . '</div>' : ''; ?>
                            <?= (old('description')) ? '<div class="error text-success mb-2" style="margin-top: -15px">Looks good!</div>' : ''; ?>


                            <button type="submit" class="btn btn-primary"><?= temp_lang('app.save'); ?></button>
                            <a href="<?= base_url($link); ?>" class="btn btn-secondary"><?= temp_lang('app.cancel'); ?></a>


                        </div>
                    </div>
                </div>

            </div>
        </form>


    </div>
</section>
<?= $this->endSection('content') ?>