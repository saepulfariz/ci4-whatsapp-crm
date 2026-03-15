<?= $this->extend('template/index') ?>

<?= $this->section('content') ?>
<?php

$can_create = auth()->user()->can('products.create');
$can_edit = auth()->user()->can('products.edit');
$can_delete = auth()->user()->can('products.delete');

?>

<div class="st-section-header">
    <h2>Master Product</h2>
    <button class="st-btn st-btn-primary" onclick="openProductModal()">+ Add Product</button>
</div>

<form id="productForm" action="<?= base_url($link) ?>" method="get">
    <div class="st-filters-bar">
        <select class="st-input-field" id="categoryFilter" name="category_id">
            <option value="">All Categories</option>
            <?php foreach ($categories as $category) : ?>
                <?php if ($category->id == $category_id): ?>
                    <option selected value="<?= $category->id ?>"><?= $category->name ?></option>
                <?php else: ?>
                    <option value="<?= $category->id ?>"><?= $category->name ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
    </div>
</form>

<div class="st-section-box table-responsive">
    <table class="st-data-table w-100" id="table2">
        <thead>
            <tr>
                <th>No</th>
                <th>Product Code</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Selling Price</th>
                <th>COGS</th>
                <th>Stock</th>
                <th>Min Stock</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="productsTableBody">
            <?php $a = 1;
            foreach ($products as $product): ?>
                <tr>
                    <td><?= $a++; ?></td>
                    <td><?= esc($product->code); ?></td>
                    <td><?= esc($product->name); ?></td>
                    <td><?= esc($product->category_name); ?></td>
                    <td>Rp <?= number_format($product->price, 0, ',', '.'); ?></td>
                    <td>Rp <?= number_format($product->cogs, 0, ',', '.'); ?></td>
                    <td><?= esc($product->stock); ?></td>
                    <td><?= esc($product->min_qty); ?></td>
                    <td>
                        <?php if ($product->status === 'Active'): ?>
                            <span class="st-badge active">Active</span>
                        <?php else: ?>
                            <span class="st-badge inactive">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="st-action-btns">
                            <?php if ($can_edit): ?>
                                <button class="st-btn-edit st-btn-small" onclick="editProduct(<?= esc($product->id) ?>)" id="product-<?= esc($product->id) ?>" data-product='<?= json_encode($product) ?>'>Edit</button>
                            <?php endif; ?>
                            <?php if ($can_delete): ?>
                                <form class="d-inline" action='<?= base_url($link . '/' . esc($product->id)); ?>' method='post' enctype='multipart/form-data'>
                                    <?= csrf_field(); ?>
                                    <input type='hidden' name='_method' value='DELETE' />
                                    <!-- GET, POST, PUT, PATCH, DELETE-->
                                    <button type='button' data-ket="<?= temp_lang('products.delete_confirm'); ?>" onclick='confirmDelete(this)' class='st-btn-delete st-btn-small'>Delete</button>
                                </form>

                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div id="productModal" class="st-modal">
    <div class="st-modal-content">
        <div class="st-modal-header">
            <h3 id="productModalTitle">Add Product</h3>
            <button class="st-modal-close" onclick="closeProductModal()">×</button>
        </div>
        <form id="productForm" action="<?= base_url($link) ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field(); ?>
            <input type='hidden' id="_method" name='_method' value='POST' />
            <div class="st-form-group">
                <label>Product Code</label>
                <input type="text" class="st-input-field" id="productCode" name="code" required="">
            </div>
            <div class="st-form-group">
                <label>Product Name</label>
                <input type="text" class="st-input-field" id="productName" name="name" required="">
            </div>
            <div class="st-form-group">
                <label>Category</label>
                <select class="st-input-field" id="productCategory" name="category_id" required="">
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?= $category->id ?>"><?= $category->name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="st-form-row">
                <div class="st-form-group">
                    <label>Selling Price</label>
                    <input type="number" class="st-input-field" id="productPrice" name="price" required="" min="0">
                </div>
                <div class="st-form-group">
                    <label>COGS</label>
                    <input type="number" class="st-input-field" id="productCOGS" name="cogs" required="" min="0">
                </div>
            </div>
            <div class="st-form-row">
                <div class="st-form-group">
                    <label>Initial Stock</label>
                    <input type="number" class="st-input-field" id="productStock" name="qty" required="" min="0">
                </div>
                <div class="st-form-group">
                    <label>Minimum Stock</label>
                    <input type="number" class="st-input-field" id="productMinStock" name="min_qty" required="" min="0">
                </div>
            </div>
            <div class="st-form-group">
                <label>Status</label>
                <select class="st-input-field" id="productStatus" name="status">
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>
            <div class="st-form-actions">
                <button type="submit" class="st-btn st-btn-primary">Save</button>
                <button type="button" class="st-btn st-btn-secondary" onclick="closeProductModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection('content') ?>

<?= $this->section('script') ?>
<script>
    const productsData = [{
            id: 1,
            code: 'DT-001',
            name: 'Sugar Donut',
            category: 'Regular Donuts',
            price: 25000,
            cogs: 10000,
            stock: 25,
            minStock: 10,
            status: 'Active'
        },
        {
            id: 2,
            code: 'DT-002',
            name: 'Chocolate Donut',
            category: 'Regular Donuts',
            price: 28000,
            cogs: 11000,
            stock: 18,
            minStock: 10,
            status: 'Active'
        },
        {
            id: 3,
            code: 'DT-003',
            name: 'Cheese Donut',
            category: 'Regular Donuts',
            price: 32000,
            cogs: 12500,
            stock: 5,
            minStock: 10,
            status: 'Active'
        },
        {
            id: 4,
            code: 'BOM-001',
            name: 'Chocolate Bomboloni',
            category: 'Bomboloni',
            price: 35000,
            cogs: 14000,
            stock: 12,
            minStock: 8,
            status: 'Active'
        },
        {
            id: 5,
            code: 'BEV-001',
            name: 'Iced Tea',
            category: 'Cold Drinks',
            price: 18000,
            cogs: 6000,
            stock: 8,
            minStock: 15,
            status: 'Active'
        },
        {
            id: 6,
            code: 'BEV-002',
            name: 'Coffee Milk',
            category: 'Coffee Drinks',
            price: 22000,
            cogs: 8000,
            stock: 15,
            minStock: 12,
            status: 'Active'
        },
        {
            id: 7,
            code: 'BEV-003',
            name: 'Thai Tea',
            category: 'Cold Drinks',
            price: 20000,
            cogs: 7000,
            stock: 3,
            minStock: 10,
            status: 'Active'
        },
    ];


    // ========================================
    // PRODUCTS PAGE
    // ========================================

    function loadProductsTable() {
        const tbody = document.getElementById('productsTableBody');
        tbody.innerHTML = '';

        productsData.forEach((product, index) => {
            const statusBadge = product.status === 'Active' ?
                '<span class="st-badge active">Active</span>' :
                '<span class="st-badge inactive">Inactive</span>';

            const stockStatus = product.stock <= product.minStock ?
                '<span class="st-badge low">Low</span>' :
                '<span class="st-badge safe">Safe</span>';

            const row = document.createElement('tr');
            row.innerHTML = `
            <td>${index + 1}</td>
            <td>${product.code}</td>
            <td>${product.name}</td>
            <td>${product.category}</td>
            <td>Rp ${product.price.toLocaleString('id-ID')}</td>
            <td>Rp ${product.cogs.toLocaleString('id-ID')}</td>
            <td>${product.stock}</td>
            <td>${product.minStock}</td>
            <td>${statusBadge}</td>
            <td>
                <div class="st-action-btns">
                    <button class="btn-edit btn-small" onclick="editProduct(${product.id})">Edit</button>
                    <button class="btn-delete btn-small" onclick="deleteProduct(${product.id})">Delete</button>
                </div>
            </td>
        `;
            tbody.appendChild(row);
        });

        // Setup search & filter
        setupProductFilters();
    }

    function setupProductFilters() {
        const searchInput = document.getElementById('productSearch');
        const categorySelect = document.getElementById('categoryFilter');

        // Populate category filter from categoriesData
        categorySelect.innerHTML = '<option value="">All Categories</option>';
        categoriesData.forEach(cat => {
            const option = document.createElement('option');
            option.value = cat.name;
            option.textContent = cat.name;
            categorySelect.appendChild(option);
        });

        const filterTable = () => {
            const searchTerm = searchInput.value.toLowerCase();
            const categoryFilter = categorySelect.value;

            document.querySelectorAll('#productsTableBody tr').forEach(row => {
                const name = row.cells[2].textContent.toLowerCase();
                const category = row.cells[3].textContent;

                const matchesSearch = name.includes(searchTerm);
                const matchesCategory = !categoryFilter || category === categoryFilter;

                row.style.display = (matchesSearch && matchesCategory) ? '' : 'none';
            });
        };

        searchInput.addEventListener('keyup', filterTable);
        categorySelect.addEventListener('change', filterTable);
    }

    function openProductModal() {
        editingProductId = null;
        document.getElementById('productModalTitle').textContent = 'Add Product';
        document.getElementById('productForm').action = `<?= base_url($link); ?>`;
        document.getElementById('_method').value = 'POST';

        document.getElementById('productStock').removeAttribute('readonly');
        document.getElementById('productForm').reset();
        document.getElementById('productModal').classList.add('show');
    }

    function editProduct(id) {

        const btn = document.getElementById('product-' + id);

        const data = JSON.parse(btn.dataset.product);

        // change url form productForm
        document.getElementById('productForm').action = `<?= base_url($link); ?>/` + id;

        // change _method to PUT
        document.getElementById('_method').value = 'PUT';

        editingProductId = id;
        document.getElementById('productModalTitle').textContent = 'Edit Product';

        if (data) {
            document.getElementById('productCode').value = data.code;
            document.getElementById('productName').value = data.name;
            document.getElementById('productCategory').value = data.category_id;

            document.getElementById('productPrice').value = data.price;

            document.getElementById('productCOGS').value = data.cogs;

            document.getElementById('productStock').setAttribute('readonly', 'true');
            document.getElementById('productStock').value = data.qty;

            document.getElementById('productMinStock').value = data.min_qty;
            document.getElementById('productStatus').value = data.status;
        }

        document.getElementById('productModal').classList.add('show');
    }

    function saveProduct(event) {
        event.preventDefault();

        const product = {
            code: document.getElementById('productCode').value,
            name: document.getElementById('productName').value,
            category: document.getElementById('productCategory').value,
            price: parseInt(document.getElementById('productPrice').value),
            cogs: parseInt(document.getElementById('productCOGS').value),
            stock: parseInt(document.getElementById('productStock').value),
            minStock: parseInt(document.getElementById('productMinStock').value),
            status: document.getElementById('productStatus').value
        };

        if (editingProductId) {
            const idx = productsData.findIndex(p => p.id === editingProductId);
            if (idx !== -1) {
                Object.assign(productsData[idx], product);
            }
            showToast('Product updated successfully!');
        } else {
            const newProduct = {
                id: Math.max(...productsData.map(p => p.id), 0) + 1,
                ...product
            };
            productsData.push(newProduct);
            showToast('Product added successfully!');
        }

        closeProductModal();
        // loadProductsTable();
        // loadSalesProductSelect();
        // loadCategoriesTable();
    }

    function closeProductModal() {
        document.getElementById('productModal').classList.remove('show');
    }

    function deleteProduct(id) {
        if (confirm('Are you sure you want to delete this product?')) {
            const idx = productsData.findIndex(p => p.id === id);
            if (idx !== -1) {
                productsData.splice(idx, 1);
                // loadProductsTable();
                // loadCategoriesTable();
                showToast('Product deleted!');
            }
        }
    }

    document.getElementById('categoryFilter').addEventListener('change', function() {
        document.getElementById('productForm').submit();
    });
</script>
<?= $this->endSection('script') ?>