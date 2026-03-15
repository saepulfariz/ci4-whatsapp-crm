<?= $this->extend('template/index') ?>

<?= $this->section('content') ?>

<div class="st-tabs-st-container">
    <button class="st-tab-btn active" data-tab="customers-tab">Customers</button>
    <button class="st-tab-btn" data-tab="groups-tab">Contact Groups</button>
</div>

<!-- Customers Tab -->
<div id="customers-tab" class="st-tab-content active">
    <div class="st-section-header">
        <h2>Customer / WhatsApp Contact</h2>
        <button class="st-btn st-btn-primary" onclick="openCustomerModal()">+ Add Customer</button>
    </div>

    <form action="<?= base_url($link); ?>" id="groupForm" method="get">
        <div class="st-filters-bar">
            <select class="st-input-field" id="groupFilter" name="group_id">
                <option value="">All Groups</option>
                <?php foreach ($groups as $group) : ?>
                    <?php if ($group->id == @$params['group_id']): ?>
                        <option selected value="<?= $group->id ?>"><?= $group->name ?></option>
                    <?php else: ?>
                        <option value="<?= $group->id ?>"><?= $group->name ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
            <select class="st-input-field" id="customerStatusFilter" name="status">
                <option value="">All Status</option>
                <option value="Active" <?= @$params['status'] == 'Active' ? 'selected' : '' ?>>Active</option>
                <option value="Inactive" <?= @$params['status'] == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
    </form>

    <?php

    $can_create = auth()->user()->can('customers.create');
    $can_edit = auth()->user()->can('customers.edit');
    $can_delete = auth()->user()->can('customers.delete');

    ?>
    <div class="st-section-box table-responsive">
        <table class="st-data-table w-100" id="table3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Customer Code</th>
                    <th>Customer Name</th>
                    <th>Phone Number</th>
                    <th>Category</th>
                    <th>Group Name</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="customersTableBody">
                <?php $a = 1;
                foreach ($customers as $customer): ?>
                    <tr>
                        <td><?= $a++; ?></td>
                        <td><?= esc($customer->code); ?></td>
                        <td><?= esc($customer->name); ?></td>
                        <td><?= esc($customer->phone); ?></td>
                        <td><?= esc($customer->category); ?></td>
                        <td><?= esc($customer->group_name); ?></td>
                        <td>
                            <?php if ($customer->status === 'Active'): ?>
                                <span class="st-badge active">Active</span>
                            <?php else: ?>
                                <span class="st-badge inactive">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="st-action-btns">
                                <?php if ($can_edit): ?>
                                    <button class="st-btn-edit st-btn-small" onclick="editCustomer(<?= esc($customer->id) ?>)" id="customer-<?= esc($customer->id) ?>" data-customer='<?= json_encode($customer) ?>'>Edit</button>
                                <?php endif; ?>
                                <?php if ($can_delete): ?>
                                    <form class="d-inline" action='<?= base_url($link . '/' . esc($customer->id)); ?>' method='post' enctype='multipart/form-data'>
                                        <?= csrf_field(); ?>
                                        <input type='hidden' name='_method' value='DELETE' />
                                        <!-- GET, POST, PUT, PATCH, DELETE-->
                                        <button type='button' data-ket="<?= temp_lang('customers.delete_confirm'); ?>" onclick='confirmDelete(this)' class='st-btn-delete st-btn-small'>Delete</button>
                                    </form>

                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
    </div>
</div>

<!-- Contact Groups Tab -->
<div id="groups-tab" class="st-tab-content">
    <div class="st-section-header">
        <h2>WhatsApp Contact Groups</h2>
        <button class="st-btn st-btn-primary" onclick="openGroupModal()">+ Add Group</button>
    </div>

    <?php

    $can_create = auth()->user()->can('groups.create');
    $can_edit = auth()->user()->can('groups.edit');
    $can_delete = auth()->user()->can('groups.delete');

    ?>
    <div class="st-section-box table-responsive">
        <table class="st-data-table w-100" id="table2">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Group Code</th>
                    <th>Group Name</th>
                    <th>Description</th>
                    <th>Total Members</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="groupsTableBody">
                <?php $a = 1;
                foreach ($groups as $group): ?>
                    <tr>
                        <td><?= $a++; ?></td>
                        <td><?= esc($group->code); ?></td>
                        <td><?= esc($group->name); ?></td>
                        <td><?= esc($group->description); ?></td>
                        <td><?= esc($group->total_member); ?></td>
                        <td>
                            <?php if ($group->status === 'Active'): ?>
                                <span class="st-badge active">Active</span>
                            <?php else: ?>
                                <span class="st-badge inactive">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="st-action-btns">
                                <?php if ($can_edit): ?>
                                    <button class="st-btn-edit st-btn-small" onclick="editGroup(<?= esc($group->id) ?>)" id="group-<?= esc($group->id) ?>" data-group='<?= json_encode($group) ?>'>Edit</button>
                                <?php endif; ?>
                                <?php if ($can_delete): ?>
                                    <form class="d-inline" action='<?= base_url($link . '/groups/' . esc($group->id)); ?>' method='post' enctype='multipart/form-data'>
                                        <?= csrf_field(); ?>
                                        <input type='hidden' name='_method' value='DELETE' />
                                        <!-- GET, POST, PUT, PATCH, DELETE-->
                                        <button type='button' data-ket="<?= temp_lang('groups.delete_confirm'); ?>" onclick='confirmDelete(this)' class='st-btn-delete st-btn-small'>Delete</button>
                                    </form>

                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="groupModal" class="st-modal">
    <div class="st-modal-content">
        <div class="st-modal-header">
            <h3 id="groupModalTitle">Add Group</h3>
            <button class="st-modal-close" onclick="closeGroupModal()">×</button>
        </div>
        <form id="groupForm" action="<?= base_url($link . '/groups'); ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field(); ?>
            <input type='hidden' id="_method" name='_method' value='POST' />

            <div class="st-form-group">
                <label>Group Code</label>
                <input type="text" class="st-input-field" id="groupCode" name="code" required="">
            </div>
            <div class="st-form-group">
                <label>Group Name</label>
                <input type="text" class="st-input-field" id="groupName" name="name" required="">
            </div>
            <div class="st-form-group">
                <label>Description</label>
                <textarea class="st-input-field" id="groupDesc" name="description" rows="3"></textarea>
            </div>
            <div class="st-form-group">
                <label>Status</label>
                <select class="st-input-field" id="groupStatus" name="status">
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>
            <div class="st-form-actions">
                <button type="submit" class="st-btn st-btn-primary">Save</button>
                <button type="button" class="st-btn st-btn-secondary" onclick="closeGroupModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<div id="customerModal" class="st-modal">
    <div class="st-modal-content">
        <div class="st-modal-header">
            <h3 id="customerModalTitle">Add Customer</h3>
            <button class="st-modal-close" onclick="closeCustomerModal()">×</button>
        </div>
        <form id="customerForm" action="<?= base_url($link); ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field(); ?>
            <input type='hidden' id="_method" name='_method' value='POST' />
            <div class="st-form-group">
                <label>Customer Code</label>
                <input type="text" class="st-input-field" id="customerCode" name="code" required="">
            </div>
            <div class="st-form-group">
                <label>Customer Name</label>
                <input type="text" class="st-input-field" id="customerName" name="name" required="">
            </div>
            <div class="st-form-group">
                <label>Phone Number</label>
                <input type="text" class="st-input-field" id="customerPhone" name="phone" required="" placeholder="0812xxxxxxx">
            </div>
            <div class="st-form-group">
                <label>Category / Tag</label>
                <input type="text" class="st-input-field" id="customerTag" name="category" placeholder="e.g., Retail, Reseller, VIP">
            </div>
            <div class="st-form-group">
                <label>Group Name</label>
                <select class="st-input-field" id="customerGroup" name="group_id" required="">
                    <option value="">Select Group</option>
                    <?php foreach ($groups as $group) : ?>
                        <option value="<?= $group->id ?>"><?= $group->name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="st-form-group">
                <label>Status</label>
                <select class="st-input-field" id="customerStatus" name="status">
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>
            <!-- <div class="st-form-group">
                <label>Notes</label>
                <textarea class="st-input-field" id="customerNotes" name="note" rows="2"></textarea>
            </div> -->
            <div class="st-form-actions">
                <button type="submit" class="st-btn st-btn-primary">Save</button>
                <button type="button" class="st-btn st-btn-secondary" onclick="closeCustomerModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection('content') ?>

<?= $this->section('script') ?>

<script>
    setDataTables('#table3');
    // ========================================
    // CUSTOMERS & GROUPS PAGE
    // ========================================

    function loadCustomersTable() {
        const tbody = document.getElementById('customersTableBody');
        tbody.innerHTML = '';

        customersData.forEach((customer, index) => {
            const statusBadge = customer.status === 'Active' ?
                '<span class="st-badge active">Active</span>' :
                '<span class="st-badge inactive">Inactive</span>';

            const row = document.createElement('tr');
            row.innerHTML = `
            <td>${index + 1}</td>
            <td>${customer.code}</td>
            <td>${customer.name}</td>
            <td>${customer.phone}</td>
            <td>${customer.tag}</td>
            <td>${customer.group}</td>
            <td>${statusBadge}</td>
            <td>
                <div class="st-action-btns">
                    <button class="btn-edit btn-small" onclick="editCustomer(${customer.id})">Edit</button>
                    <button class="btn-delete btn-small" onclick="deleteCustomer(${customer.id})">Delete</button>
                </div>
            </td>
        `;
            tbody.appendChild(row);
        });

        setupCustomerFilters();
    }

    function setupCustomerFilters() {
        const searchInput = document.getElementById('customerSearch');
        const groupSelect = document.getElementById('groupFilter');
        const statusSelect = document.getElementById('customerStatusFilter');

        // Populate group filter
        groupSelect.innerHTML = '<option value="">All Groups</option>';
        groupsData.forEach(group => {
            const option = document.createElement('option');
            option.value = group.name;
            option.textContent = group.name;
            groupSelect.appendChild(option);
        });

        const filterTable = () => {
            const searchTerm = searchInput.value.toLowerCase();
            const groupFilter = groupSelect.value;
            const statusFilter = statusSelect.value;

            document.querySelectorAll('#customersTableBody tr').forEach(row => {
                const name = row.cells[2].textContent.toLowerCase();
                const phone = row.cells[3].textContent.toLowerCase();
                const group = row.cells[5].textContent;
                const status = row.cells[6].textContent;

                const matchesSearch = name.includes(searchTerm) || phone.includes(searchTerm);
                const matchesGroup = !groupFilter || group === groupFilter;
                const matchesStatus = !statusFilter || status.includes(statusFilter);

                row.style.display = (matchesSearch && matchesGroup && matchesStatus) ? '' : 'none';
            });
        };

        searchInput.addEventListener('keyup', filterTable);
        groupSelect.addEventListener('change', filterTable);
        statusSelect.addEventListener('change', filterTable);
    }

    function openCustomerModal() {
        editingCustomerId = null;
        document.getElementById('customerModalTitle').textContent = 'Add Customer';
        document.getElementById('customerForm').action = `<?= base_url($link); ?>`;
        document.getElementById('_method').value = 'POST';

        document.getElementById('customerForm').reset();
        // populateCustomerGroupSelect();
        document.getElementById('customerModal').classList.add('show');
    }

    function populateCustomerGroupSelect() {
        const select = document.getElementById('customerGroup');
        select.innerHTML = '<option value="">Select Group</option>';
        groupsData.forEach(group => {
            const option = document.createElement('option');
            option.value = group.name;
            option.textContent = group.name;
            select.appendChild(option);
        });
    }

    function editCustomer(id) {
        const btn = document.getElementById('customer-' + id);

        const data = JSON.parse(btn.dataset.customer);

        document.getElementById('customerForm').action = `<?= base_url($link); ?>/` + id;

        document.querySelector('#customerForm input[name="_method"]').value = 'PUT';

        editingCustomerId = id;
        document.getElementById('customerModalTitle').textContent = 'Edit Customer';

        if (data) {
            document.getElementById('customerCode').value = data.code;
            document.getElementById('customerName').value = data.name;
            document.getElementById('customerPhone').value = data.phone;
            document.getElementById('customerTag').value = data.category;
            // populateCustomerGroupSelect();
            document.getElementById('customerGroup').value = data.group_id;
            document.getElementById('customerStatus').value = data.status;
            // document.getElementById('customerNotes').value = data.note;
        }
        document.getElementById('customerModal').classList.add('show');
    }

    function saveCustomer(event) {
        event.preventDefault();

        const customer = {
            code: document.getElementById('customerCode').value,
            name: document.getElementById('customerName').value,
            phone: document.getElementById('customerPhone').value,
            tag: document.getElementById('customerTag').value,
            group: document.getElementById('customerGroup').value,
            status: document.getElementById('customerStatus').value,
            notes: document.getElementById('customerNotes').value
        };

        if (editingCustomerId) {
            const idx = customersData.findIndex(c => c.id === editingCustomerId);
            if (idx !== -1) {
                Object.assign(customersData[idx], customer);
            }
            showToast('Customer updated!');
        } else {
            const newCustomer = {
                id: Math.max(...customersData.map(c => c.id), 0) + 1,
                ...customer
            };
            customersData.push(newCustomer);
            showToast('Customer added!');
        }

        closeCustomerModal();
        loadCustomersTable();
        loadBroadcastContactList();
    }

    function closeCustomerModal() {
        document.getElementById('customerModal').classList.remove('show');
    }

    function deleteCustomer(id) {
        if (confirm('Delete this customer?')) {
            const idx = customersData.findIndex(c => c.id === id);
            if (idx !== -1) {
                customersData.splice(idx, 1);
                loadCustomersTable();
                showToast('Customer deleted!');
            }
        }
    }

    function loadGroupsTable() {
        const tbody = document.getElementById('groupsTableBody');
        tbody.innerHTML = '';

        groupsData.forEach((group, index) => {
            const statusBadge = group.status === 'Active' ?
                '<span class="st-badge active">Active</span>' :
                '<span class="st-badge inactive">Inactive</span>';

            const totalMembers = customersData.filter(c => c.group === group.name).length;

            const row = document.createElement('tr');
            row.innerHTML = `
            <td>${index + 1}</td>
            <td>${group.code}</td>
            <td>${group.name}</td>
            <td>${group.description}</td>
            <td>${totalMembers}</td>
            <td>${statusBadge}</td>
            <td>
                <div class="st-action-btns">
                    <button class="btn-edit btn-small" onclick="editGroup(${group.id})">Edit</button>
                    <button class="btn-delete btn-small" onclick="deleteGroup(${group.id})">Delete</button>
                </div>
            </td>
        `;
            tbody.appendChild(row);
        });

        setupGroupFilters();
    }

    function setupGroupFilters() {
        const searchInput = document.getElementById('groupSearch');
        if (!searchInput) return;

        searchInput.addEventListener('keyup', () => {
            const searchTerm = searchInput.value.toLowerCase();

            document.querySelectorAll('#groupsTableBody tr').forEach(row => {
                const name = row.cells[2].textContent.toLowerCase();
                const code = row.cells[1].textContent.toLowerCase();

                row.style.display = (name.includes(searchTerm) || code.includes(searchTerm)) ? '' : 'none';
            });
        });
    }

    function openGroupModal() {
        editingGroupId = null;
        document.getElementById('groupModalTitle').textContent = 'Add Group';
        document.getElementById('groupForm').action = `<?= base_url($link . '/groups'); ?>`;
        document.getElementById('_method').value = 'POST';

        document.getElementById('groupForm').reset();
        document.getElementById('groupModal').classList.add('show');
    }

    function editGroup(id) {
        const btn = document.getElementById('group-' + id);

        const data = JSON.parse(btn.dataset.group);

        document.getElementById('groupForm').action = `<?= base_url($link); ?>/groups/` + id;

        document.querySelector('#groupForm input[name="_method"]').value = 'PUT';

        editingGroupId = id;
        document.getElementById('groupModalTitle').textContent = 'Edit Group';

        if (data) {
            document.getElementById('groupCode').value = data.code;
            document.getElementById('groupName').value = data.name;
            document.getElementById('groupDesc').value = data.description;
            document.getElementById('groupStatus').value = data.status;
        }
        document.getElementById('groupModal').classList.add('show');
    }

    function saveGroup(event) {
        event.preventDefault();

        const group = {
            code: document.getElementById('groupCode').value,
            name: document.getElementById('groupName').value,
            description: document.getElementById('groupDesc').value,
            status: document.getElementById('groupStatus').value
        };

        if (editingGroupId) {
            const idx = groupsData.findIndex(g => g.id === editingGroupId);
            if (idx !== -1) {
                Object.assign(groupsData[idx], group);
            }
            showToast('Group updated!');
        } else {
            const newGroup = {
                id: Math.max(...groupsData.map(g => g.id), 0) + 1,
                ...group
            };
            groupsData.push(newGroup);
            showToast('Group added!');
        }

        closeGroupModal();
        loadGroupsTable();
        setupCustomerFilters();
    }

    function closeGroupModal() {
        document.getElementById('groupModal').classList.remove('show');
    }

    function deleteGroup(id) {
        const group = groupsData.find(g => g.id === id);
        if (!group) return;

        const membersInGroup = customersData.filter(c => c.group === group.name).length;
        if (membersInGroup > 0) {
            showToast(`Cannot delete group with ${membersInGroup} member(s)!`);
            return;
        }

        if (confirm('Delete this group?')) {
            const idx = groupsData.findIndex(g => g.id === id);
            if (idx !== -1) {
                groupsData.splice(idx, 1);
                loadGroupsTable();
                showToast('Group deleted!');
            }
        }
    }

    // if change group_id or status then submit form
    document.getElementById('groupFilter').addEventListener('change', function() {
        document.getElementById('groupForm').submit();
    });
    document.getElementById('customerStatusFilter').addEventListener('change', function() {
        document.getElementById('groupForm').submit();
    });
</script>
<?= $this->endSection('script') ?>