<?= $this->extend('template/index') ?>

<?= $this->section('content') ?>
<?php

$can_create = auth()->user()->can('stocks.create');

?>

<div class="st-section-header">
    <h2>Stock Mutations</h2>
    <button class="st-btn st-btn-primary" onclick="openStockModal()">+ Add Stock</button>
</div>

<form id="stockForm" action="<?= base_url($link) ?>" method="get">
    <div class="st-filters-bar">
        <select class="st-input-field" id="typeFilter" name="type">
            <option value="">All Types</option>
            <?php foreach ($types as $type) : ?>
                <?php if ($type['id'] == $type_id): ?>
                    <option selected value="<?= $type['id'] ?>"><?= $type['name'] ?></option>
                <?php else: ?>
                    <option value="<?= $type['id'] ?>"><?= $type['name'] ?></option>
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
                <th><?= temp_lang('stocks.date') ?></th>
                <th><?= temp_lang('stocks.product_code') ?></th>
                <th><?= temp_lang('stocks.product_name') ?></th>
                <th><?= temp_lang('stocks.type') ?></th>
                <th><?= temp_lang('stocks.qty') ?></th>
                <th><?= temp_lang('stocks.prev_stock') ?></th>
                <th><?= temp_lang('stocks.current_stock') ?></th>
                <th><?= temp_lang('stocks.note') ?></th>
                <th><?= temp_lang('stocks.input_by') ?></th>
            </tr>
        </thead>
        <tbody id="productsTableBody">
            <?php $a = 1;
            foreach ($stocks as $stock): ?>
                <tr>
                    <td><?= $a++; ?></td>
                    <td><?= esc($stock->date); ?></td>
                    <td><?= esc($stock->product_code); ?></td>
                    <td><?= esc($stock->product_name); ?></td>
                    <td><?= esc($stock->type); ?></td>
                    <td><?= esc($stock->qty); ?></td>
                    <td><?= esc($stock->prev_stock); ?></td>
                    <td><?= esc($stock->current_stock); ?></td>
                    <td><?= esc($stock->note); ?></td>
                    <td><?= esc($stock->input_by); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<div id="stockModal" class="st-modal">
    <div class="st-modal-content">
        <div class="st-modal-header">
            <h3>Add Stock Mutation</h3>
            <button class="st-modal-close" onclick="closeStockModal()">×</button>
        </div>
        <form id="stockForm" action="<?= base_url($link) ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field(); ?>
            <div class="st-form-group">
                <label>Product</label>
                <select class="st-input-field" id="mutationProduct" name="product_id" required="">
                    <option value="">Select Product</option>
                    <?php foreach ($products as $product) : ?>
                        <option selected value="<?= $product->id ?>"><?= $product->code ?> - <?= $product->name ?></option>

                    <?php endforeach; ?>
                </select>
            </div>
            <div class="st-form-group">
                <label>Mutation Type</label>
                <select class="st-input-field" id="mutationType" name="type" required="">
                    <option value="" disabled>Select Type</option>
                    <?php foreach ($types as $type) : ?>
                        <?php if ($type['id'] == $type_id): ?>
                            <option selected value="<?= $type['id'] ?>"><?= $type['name'] ?></option>
                        <?php else: ?>
                            <option value="<?= $type['id'] ?>"><?= $type['name'] ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="st-form-group">
                <label>Quantity</label>
                <input type="number" class="st-input-field" id="mutationQuantity" name="qty" required="" min="1">
            </div>
            <div class="st-form-group">
                <label>Note</label>
                <textarea class="st-input-field" id="mutationNotes" name="note" rows="3"></textarea>
            </div>
            <div class="st-form-group">
                <label>Date</label>
                <input type="datetime-local" class="st-input-field" id="mutationDate" name="date" required="">
            </div>
            <div class="st-form-actions">
                <button type="submit" class="st-btn st-btn-primary">Save</button>
                <button type="button" class="st-btn st-btn-secondary" onclick="closeStockModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection('content') ?>

<?= $this->section('script') ?>
<script>
    // ========================================
    // STOCK PAGE
    // ========================================


    function loadStockTable() {
        const tbody = document.getElementById('stockTableBody');
        tbody.innerHTML = '';

        stockMutationsData.forEach((mutation, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
            <td>${index + 1}</td>
            <td>${mutation.date}</td>
            <td>${mutation.productCode}</td>
            <td>${mutation.productName}</td>
            <td>${mutation.type}</td>
            <td>${mutation.qty}</td>
            <td>${mutation.prevStock}</td>
            <td>${mutation.currentStock}</td>
            <td>${mutation.notes}</td>
            <td>${mutation.user}</td>
        `;
            tbody.appendChild(row);
        });

        // Setup stock filters
        setupStockFilters();
    }

    function setupStockFilters() {
        const dateInput = document.getElementById('stockDateFilter');
        const searchInput = document.getElementById('stockProductSearch');
        const typeSelect = document.getElementById('mutationTypeFilter');

        const filterTable = () => {
            const selectedDate = dateInput.value;
            const searchTerm = searchInput.value.toLowerCase();
            const selectedType = typeSelect.value;

            document.querySelectorAll('#stockTableBody tr').forEach(row => {
                const date = row.cells[1].textContent;
                const productName = row.cells[3].textContent.toLowerCase();
                const type = row.cells[4].textContent;

                const matchesDate = !selectedDate || date.includes(selectedDate);
                const matchesSearch = searchTerm === '' || productName.includes(searchTerm);
                const matchesType = !selectedType || type === selectedType;

                row.style.display = (matchesDate && matchesSearch && matchesType) ? '' : 'none';
            });
        };

        dateInput.addEventListener('change', filterTable);
        searchInput.addEventListener('keyup', filterTable);
        typeSelect.addEventListener('change', filterTable);
    }

    function openStockModal() {
        document.getElementById('stockForm').reset();
        // populateStockProductSelect();
        document.getElementById('mutationDate').value = new Date().toISOString().slice(0, 16);
        document.getElementById('stockModal').classList.add('show');
    }

    function populateStockProductSelect() {
        const select = document.getElementById('mutationProduct');
        select.innerHTML = '<option value="">Select Product</option>';
        productsData.forEach(product => {
            const option = document.createElement('option');
            option.value = product.id;
            option.textContent = `${product.code} - ${product.name}`;
            select.appendChild(option);
        });
    }

    // Populate product category dropdown in product st_modal
    function populateProductCategorySelect() {
        const select = document.getElementById('productCategory');
        select.innerHTML = '<option value="">Select Category</option>';
        categoriesData.forEach(cat => {
            const option = document.createElement('option');
            option.value = cat.name;
            option.textContent = cat.name;
            select.appendChild(option);
        });
    }

    function saveStockMutation(event) {
        event.preventDefault();

        const productId = parseInt(document.getElementById('mutationProduct').value);
        const product = productsData.find(p => p.id === productId);
        if (!product) return;

        const quantity = parseInt(document.getElementById('mutationQuantity').value);
        const type = document.getElementById('mutationType').value;

        const newMutation = {
            id: Math.max(...stockMutationsData.map(m => m.id), 0) + 1,
            date: new Date(document.getElementById('mutationDate').value).toISOString().split('T')[0],
            productCode: product.code,
            productName: product.name,
            type: type,
            qty: quantity,
            prevStock: product.stock,
            currentStock: product.stock + quantity,
            notes: document.getElementById('mutationNotes').value,
            user: 'Admin'
        };

        // Update product stock
        product.stock = newMutation.currentStock;

        stockMutationsData.push(newMutation);
        closeStockModal();
        loadStockTable();
        showToast('Stock mutation recorded!');
    }

    function closeStockModal() {
        document.getElementById('stockModal').classList.remove('show');
    }


    document.getElementById('typeFilter').addEventListener('change', function() {
        document.getElementById('stockForm').submit();
    });
</script>
<?= $this->endSection('script') ?>