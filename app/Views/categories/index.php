<?= $this->extend('template/index') ?>

<?= $this->section('content') ?>


<div class="st-section-header">
    <h2>Master <?= $title ?></h2>

    <?php

    $can_create = auth()->user()->can('categories.create');
    $can_edit = auth()->user()->can('categories.edit');
    $can_delete = auth()->user()->can('categories.delete');

    ?>

    <?php if ($can_create): ?>
        <button class="st-btn st-btn-primary" onclick="openCategoryModal()">+ <?= temp_lang('app.new'); ?> <?= temp_lang('categories.category'); ?></button>
    <?php endif; ?>
</div>


<div class="st-section-box table-responsive">
    <table class="st-data-table w-100" id="table2">
        <thead>
            <tr>
                <th>#</th>
                <th><?= temp_lang('categories.code'); ?></th>
                <th><?= temp_lang('categories.name'); ?></th>
                <th><?= temp_lang('categories.description'); ?></th>
                <th><?= temp_lang('categories.status'); ?></th>
                <th><?= temp_lang('categories.total'); ?> <?= temp_lang('products.product'); ?></th>
                <th><?= temp_lang('app.action'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php $a = 1;
            foreach ($categories as $category): ?>
                <tr>
                    <td><?= $a++; ?></td>
                    <td><?= esc($category->code); ?></td>
                    <td><?= esc($category->name); ?></td>
                    <td><?= esc($category->description); ?></td>
                    <td>
                        <?php if ($category->status === 'Active'): ?>
                            <span class="st-badge active">Active</span>
                        <?php else: ?>
                            <span class="st-badge inactive">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td><?= esc($category->product_count); ?></td>
                    <td>
                        <div class="st-action-btns">
                            <?php if ($can_edit): ?>
                                <button class="st-btn-edit st-btn-small" type="button" onclick="editCategory(<?= esc($category->id) ?>)" id="category-<?= esc($category->id) ?>" data-category='<?= json_encode($category) ?>'>Edit</button>
                            <?php endif; ?>
                            <?php if ($can_delete): ?>

                                <form class="d-inline" action='<?= base_url($link . '/' . esc($category->id)); ?>' method='post' enctype='multipart/form-data'>
                                    <?= csrf_field(); ?>
                                    <input type='hidden' name='_method' value='DELETE' />
                                    <!-- GET, POST, PUT, PATCH, DELETE-->
                                    <button type='button' data-ket="<?= temp_lang('categories.delete_confirm'); ?>" onclick='confirmDelete(this)' class='st-btn-delete st-btn-small'>Delete</button>
                                </form>

                            <?php endif; ?>
                        </div>

                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<!-- Category Modal -->
<div id="categoryModal" class="st-modal">
    <div class="st-modal-content">
        <div class="st-modal-header">
            <h3 id="categoryModalTitle"><?= temp_lang('app.new'); ?> <?= temp_lang('categories.category'); ?></h3>
            <button class="st-modal-close" onclick="closeCategoryModal()">&times;</button>
        </div>
        <form id="categoryForm" action="<?= base_url($link) ?>" method="post" enctype="multipart/form-data" onsubmit="saveCategory(event)">
            <?= csrf_field(); ?>
            <input type='hidden' id="_method" name='_method' value='POST' />
            <div class="st-form-group">
                <label><?= temp_lang('categories.code'); ?></label>
                <input type="text" class="st-input-field" id="categoryCode" name="code" required>
            </div>
            <div class="st-form-group">
                <label><?= temp_lang('categories.name'); ?></label>
                <input type="text" class="st-input-field" id="categoryName" name="name" required>
            </div>
            <div class="st-form-group">
                <label><?= temp_lang('categories.description'); ?></label>
                <textarea class="st-input-field" id="categoryDesc" name="description" rows="3"></textarea>
            </div>
            <div class="st-form-group">
                <label><?= temp_lang('categories.status'); ?></label>
                <select class="st-input-field" id="categoryStatus" name="status">
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>
            <div class="st-form-actions">
                <button type="submit" class="st-btn st-btn-primary"><?= temp_lang('app.save'); ?></button>
                <button type="button" class="st-btn st-btn-secondary" onclick="closeCategoryModal()"><?= temp_lang('app.cancel'); ?></button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection('content') ?>

<?= $this->section('script') ?>
<script>
    const categoriesData = [{
            id: 1,
            code: 'CAT-001',
            name: 'Regular Donuts',
            description: 'Classic donut varieties',
            status: 'Active'
        },
        {
            id: 2,
            code: 'CAT-002',
            name: 'Premium Donuts',
            description: 'Premium donut selection',
            status: 'Active'
        },
        {
            id: 3,
            code: 'CAT-003',
            name: 'Bomboloni',
            description: 'Bomboloni pastries',
            status: 'Active'
        },
        {
            id: 4,
            code: 'CAT-004',
            name: 'Cold Drinks',
            description: 'Cold beverage options',
            status: 'Active'
        },
        {
            id: 5,
            code: 'CAT-005',
            name: 'Coffee Drinks',
            description: 'Hot and cold coffee',
            status: 'Active'
        },
    ];

    // ========================================
    // CATEGORIES PAGE
    // ========================================

    function loadCategoriesTable() {
        const tbody = document.getElementById('categoriesTableBody');
        tbody.innerHTML = '';

        categoriesData.forEach((category, index) => {
            const statusBadge = category.status === 'Active' ?
                '<span class="st-badge active">Active</span>' :
                '<span class="st-badge inactive">Inactive</span>';

            const totalProducts = productsData.filter(p => p.category === category.name).length;

            const row = document.createElement('tr');
            let rowData = `
                            <td>${index + 1}</td>
                            <td>${category.code}</td>
                            <td>${category.name}</td>
                            <td>${category.description}</td>
                            <td>${statusBadge}</td>
                            <td>${totalProducts}</td>
                            <td>
                                <div class="st-action-btns">
                            
                        `;

            <?php if ($can_edit): ?>
                rowData += `<button class="btn-edit btn-small" onclick="editCategory(${category.id})">Edit</button>`;
            <?php endif; ?>
            <?php if ($can_delete): ?>
                rowData += `<button class="btn-delete btn-small" onclick="deleteCategory(${category.id})">Delete</button>`;
            <?php endif; ?>

            rowData += `</div></td>`;
            row.innerHTML = rowData;

            tbody.appendChild(row);
        });

        // Setup search filter
        setupCategoryFilters();
    }



    function setupCategoryFilters() {
        const searchInput = document.getElementById('categorySearch');
        if (!searchInput) return;

        searchInput.addEventListener('keyup', () => {
            const searchTerm = searchInput.value.toLowerCase();

            document.querySelectorAll('#categoriesTableBody tr').forEach(row => {
                const name = row.cells[2].textContent.toLowerCase();
                const code = row.cells[1].textContent.toLowerCase();

                const matchesSearch = name.includes(searchTerm) || code.includes(searchTerm);
                row.style.display = matchesSearch ? '' : 'none';
            });
        });
    }

    function openCategoryModal() {
        editingCategoryId = null;
        document.getElementById('categoryModalTitle').textContent = '<?= temp_lang('app.new'); ?> <?= temp_lang('categories.category'); ?>';
        document.getElementById('categoryForm').action = `<?= base_url($link); ?>`;
        document.getElementById('_method').value = 'POST';
        document.getElementById('categoryForm').reset();
        document.getElementById('categoryModal').classList.add('show');
    }

    function editCategory(id) {
        // const category = categoriesData.find(c => c.id === id);
        // if (!category) return;

        // get this form id category-id
        const btn = document.getElementById('category-' + id);
        // get data-category from this attribute button

        const data = JSON.parse(btn.dataset.category);

        // change url form categoryForm
        document.getElementById('categoryForm').action = `<?= base_url($link); ?>/` + id;

        // change _method to PUT
        document.getElementById('_method').value = 'PUT';


        editingCategoryId = id;
        document.getElementById('categoryModalTitle').textContent = '<?= temp_lang('app.edit'); ?> <?= temp_lang('categories.category'); ?>';

        if (data) {
            document.getElementById('categoryCode').value = data.code;
            document.getElementById('categoryName').value = data.name;
            document.getElementById('categoryDesc').value = data.description;
            document.getElementById('categoryStatus').value = data.status;
        }

        document.getElementById('categoryModal').classList.add('show');
    }

    function saveCategory(event) {
        event.preventDefault();

        const category = {
            code: document.getElementById('categoryCode').value,
            name: document.getElementById('categoryName').value,
            description: document.getElementById('categoryDesc').value,
            status: document.getElementById('categoryStatus').value
        };

        if (editingCategoryId) {
            const idx = categoriesData.findIndex(c => c.id === editingCategoryId);
            if (idx !== -1) {
                Object.assign(categoriesData[idx], category);
            }
            showToast('Category updated successfully!');
        } else {
            const newCategory = {
                id: Math.max(...categoriesData.map(c => c.id), 0) + 1,
                ...category
            };
            categoriesData.push(newCategory);
            showToast('Category added successfully!');
        }

        closeCategoryModal();
        loadCategoriesTable();
        setupProductFilters();
    }

    function closeCategoryModal() {
        document.getElementById('categoryModal').classList.remove('show');
    }

    // function deleteCategory(id) {
    //     const category = categoriesData.find(c => c.id === id);
    //     if (!category) return;

    //     const productsInCategory = productsData.filter(p => p.category === category.name).length;
    //     if (productsInCategory > 0) {
    //         showToast(`Cannot delete category with ${productsInCategory} product(s)!`);
    //         return;
    //     }

    //     if (confirm('Are you sure you want to delete this category?')) {
    //         const idx = categoriesData.findIndex(c => c.id === id);
    //         if (idx !== -1) {
    //             categoriesData.splice(idx, 1);
    //             loadCategoriesTable();
    //             showToast('Category deleted!');
    //         }
    //     }
    // }

    function deleteCategory(id) {
        if (!confirm("<?= temp_lang('categories.delete_confirm'); ?>")) {
            return;
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `<?= base_url($link); ?>/${id}`;

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '<?= csrf_token(); ?>';
        csrf.value = '<?= csrf_hash(); ?>';

        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';

        form.appendChild(csrf);
        form.appendChild(method);

        document.body.appendChild(form);
        form.submit();
    }
</script>


<?= $this->endSection('script') ?>