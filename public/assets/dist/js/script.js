// ========================================
// Stock & Sales Management - JavaScript
// Application: Sweet Treats Donut Shop
// ========================================

// ========================================
// DUMMY DATA - Static Product & Transaction Data
// ========================================

// Category Master Data
const categoriesData = [
    { id: 1, code: 'CAT-001', name: 'Regular Donuts', description: 'Classic donut varieties', status: 'Active' },
    { id: 2, code: 'CAT-002', name: 'Premium Donuts', description: 'Premium donut selection', status: 'Active' },
    { id: 3, code: 'CAT-003', name: 'Bomboloni', description: 'Bomboloni pastries', status: 'Active' },
    { id: 4, code: 'CAT-004', name: 'Cold Drinks', description: 'Cold beverage options', status: 'Active' },
    { id: 5, code: 'CAT-005', name: 'Coffee Drinks', description: 'Hot and cold coffee', status: 'Active' },
];

const productsData = [
    { id: 1, code: 'DT-001', name: 'Sugar Donut', category: 'Regular Donuts', price: 25000, cogs: 10000, stock: 25, minStock: 10, status: 'Active' },
    { id: 2, code: 'DT-002', name: 'Chocolate Donut', category: 'Regular Donuts', price: 28000, cogs: 11000, stock: 18, minStock: 10, status: 'Active' },
    { id: 3, code: 'DT-003', name: 'Cheese Donut', category: 'Regular Donuts', price: 32000, cogs: 12500, stock: 5, minStock: 10, status: 'Active' },
    { id: 4, code: 'BOM-001', name: 'Chocolate Bomboloni', category: 'Bomboloni', price: 35000, cogs: 14000, stock: 12, minStock: 8, status: 'Active' },
    { id: 5, code: 'BEV-001', name: 'Iced Tea', category: 'Cold Drinks', price: 18000, cogs: 6000, stock: 8, minStock: 15, status: 'Active' },
    { id: 6, code: 'BEV-002', name: 'Coffee Milk', category: 'Coffee Drinks', price: 22000, cogs: 8000, stock: 15, minStock: 12, status: 'Active' },
    { id: 7, code: 'BEV-003', name: 'Thai Tea', category: 'Cold Drinks', price: 20000, cogs: 7000, stock: 3, minStock: 10, status: 'Active' },
];

const stockMutationsData = [
    { id: 1, date: '2026-03-10', productCode: 'DT-001', productName: 'Sugar Donut', type: 'Stock In', qty: 20, prevStock: 5, currentStock: 25, notes: 'Morning stock', user: 'Admin' },
    { id: 2, date: '2026-03-10', productCode: 'BEV-001', productName: 'Iced Tea', type: 'Adjustment Decrease', qty: 7, prevStock: 15, currentStock: 8, notes: 'Daily sales', user: 'Admin' },
    { id: 3, date: '2026-03-09', productCode: 'DT-002', productName: 'Chocolate Donut', type: 'Stock In', qty: 15, prevStock: 3, currentStock: 18, notes: 'Restocking', user: 'Admin' },
];

const salesHistoryData = [
    { id: 1, date: '2026-03-10 14:30', transNo: 'TRX-20260310-001', items: 3, total: 85000, method: 'Cash', cashier: 'Admin', status: 'Completed' },
    { id: 2, date: '2026-03-10 14:15', transNo: 'TRX-20260310-002', items: 2, total: 120000, method: 'Transfer', cashier: 'Admin', status: 'Completed' },
    { id: 3, date: '2026-03-10 14:00', transNo: 'TRX-20260310-003', items: 5, total: 220000, method: 'QRIS', cashier: 'Admin', status: 'Completed' },
];

const salesReportData = [
    { id: 1, date: '2026-03-10', transNo: 'TRX-20260310-001', product: 'Sugar Donut', qty: 2, price: 25000, cogs: 10000, total: 50000, profit: 30000 },
    { id: 2, date: '2026-03-10', transNo: 'TRX-20260310-001', product: 'Iced Tea', qty: 1, price: 18000, cogs: 6000, total: 18000, profit: 12000 },
    { id: 3, date: '2026-03-10', transNo: 'TRX-20260310-002', product: 'Coffee Milk', qty: 2, price: 22000, cogs: 8000, total: 44000, profit: 28000 },
];

// Contact Groups Data
const groupsData = [
    { id: 1, code: 'GRP-001', name: 'Regular Customers', description: 'Regular customers group', status: 'Active' },
    { id: 2, code: 'GRP-002', name: 'Premium Customers', description: 'VIP and premium clients', status: 'Active' },
    { id: 3, code: 'GRP-003', name: 'Reseller Group', description: 'Reseller partners', status: 'Active' },
    { id: 4, code: 'GRP-004', name: 'Drink Buyers', description: 'Beverage purchasers', status: 'Active' },
    { id: 5, code: 'GRP-005', name: 'Donut Buyers', description: 'Donut customers', status: 'Active' },
];

// Customers Data
const customersData = [
    { id: 1, code: 'CUST-001', name: 'Andi', phone: '0812345678901', tag: 'Retail', group: 'Regular Customers', status: 'Active', notes: 'Regular customer since 2024' },
    { id: 2, code: 'CUST-002', name: 'Budi', phone: '0821345678901', tag: 'Reseller', group: 'Reseller Group', status: 'Active', notes: 'Bulk buyer' },
    { id: 3, code: 'CUST-003', name: 'Sinta', phone: '0857345678901', tag: 'VIP', group: 'Premium Customers', status: 'Active', notes: 'High-value customer' },
    { id: 4, code: 'CUST-004', name: 'Doni', phone: '0813345678901', tag: 'Cafe Owner', group: 'Drink Buyers', status: 'Active', notes: '' },
    { id: 5, code: 'CUST-005', name: 'Eka', phone: '0812987654321', tag: 'Retail', group: 'Regular Customers', status: 'Active', notes: '' },
];

// Broadcast History Data
const broadcastHistoryData = [
    { id: 1, date: '2026-03-10 10:30', title: 'New Product Launch - Sugar Donut', recipientType: 'All Contacts', recipients: 5, attachment: 'Image', status: 'Sent', createdBy: 'Admin' },
    { id: 2, date: '2026-03-09 14:15', title: 'Weekend Promo - 20% Discount', recipientType: 'By Group', recipients: 3, attachment: 'No', status: 'Sent', createdBy: 'Admin' },
    { id: 3, date: '2026-03-08 09:00', title: 'Spring Collection Announcement', recipientType: 'Selected', recipients: 1, attachment: 'Image', status: 'Draft', createdBy: 'Admin' },
];

let currentCart = [];
let editingProductId = null;
let editingCategoryId = null;
let editingCustomerId = null;
let editingGroupId = null;
let selectedCustomersForBroadcast = [];
let broadcastImageFile = null;

// ========================================
// PAGE NAVIGATION
// ========================================

document.querySelectorAll('.st-nav-item').forEach(btn => {
    if (btn.classList.contains('st-submenu-toggle')) {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const submenuId = btn.dataset.submenu + '-submenu';
            const submenu = document.getElementById(submenuId);
            btn.classList.toggle('open');
            submenu.classList.toggle('open');
        });
    } else {
        btn.addEventListener('click', (e) => {
            const page = btn.dataset.page;
            console.log(page);
            if (page) navigateToPage(page);
            
            // Update active state
            document.querySelectorAll('.st-nav-item, .st-nav-subitem').forEach(item => item.classList.remove('active'));
            btn.classList.add('active');
        });
    }
});

document.querySelectorAll('.st-nav-subitem').forEach(btn => {
    btn.addEventListener('click', (e) => {
        const page = btn.dataset.page;
        navigateToPage(page);
        
        // Update active state
        document.querySelectorAll('.st-nav-item, .st-nav-subitem').forEach(item => item.classList.remove('active'));
        btn.classList.add('active');
    });
});

function navigateToPage(pageName) {
    // Hide all pages
    document.querySelectorAll('.st-page').forEach(page => page.classList.remove('active'));
    
    // Show selected page
    const page = document.getElementById(pageName);
    if (page) {
        page.classList.add('active');
        
        // Update page title
        const titles = {
            'dashboard': 'Dashboard',
            'master-product': 'Master Product',
            'master-category': 'Master Category Product',
            'master-customer': 'Customer / WhatsApp Contact',
            'broadcast': 'WhatsApp Broadcast',
            'stock': 'Stock Mutations',
            'sales': 'Sales',
            'reports': 'Reports'
        };
        document.querySelector('.st-page-title').textContent = titles[pageName] || 'Dashboard';
        
        // Load data based on page
        if (pageName === 'master-product') {
            loadProductsTable();
        } else if (pageName === 'master-category') {
            loadCategoriesTable();
        } else if (pageName === 'master-customer') {
            loadCustomersTable();
            loadGroupsTable();
        } else if (pageName === 'broadcast') {
            loadBroadcastPage();
        } else if (pageName === 'stock') {
            loadStockTable();
        } else if (pageName === 'sales') {
            loadSalesPage();
        } else if (pageName === 'reports') {
            loadReportsData();
        }
    }
}

// ========================================
// PRODUCTS PAGE
// ========================================

function loadProductsTable() {
    const tbody = document.getElementById('productsTableBody');
    tbody.innerHTML = '';
    
    productsData.forEach((product, index) => {
        const statusBadge = product.status === 'Active' 
            ? '<span class="st-badge active">Active</span>' 
            : '<span class="st-badge inactive">Inactive</span>';
        
        const stockStatus = product.stock <= product.minStock 
            ? '<span class="st-badge low">Low</span>' 
            : '<span class="st-badge safe">Safe</span>';
        
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
    document.getElementById('productForm').reset();
    document.getElementById('productModal').classList.add('show');
}

function editProduct(id) {
    const product = productsData.find(p => p.id === id);
    if (!product) return;
    
    editingProductId = id;
    document.getElementById('productModalTitle').textContent = 'Edit Product';
    document.getElementById('productCode').value = product.code;
    document.getElementById('productName').value = product.name;
    document.getElementById('productCategory').value = product.category;
    document.getElementById('productPrice').value = product.price;
    document.getElementById('productCOGS').value = product.cogs;
    document.getElementById('productStock').value = product.stock;
    document.getElementById('productMinStock').value = product.minStock;
    document.getElementById('productStatus').value = product.status;
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
        const newProduct = { id: Math.max(...productsData.map(p => p.id), 0) + 1, ...product };
        productsData.push(newProduct);
        showToast('Product added successfully!');
    }
    
    closeProductModal();
    loadProductsTable();
    loadSalesProductSelect();
    loadCategoriesTable();
}

function closeProductModal() {
    document.getElementById('productModal').classList.remove('show');
}

function deleteProduct(id) {
    if (confirm('Are you sure you want to delete this product?')) {
        const idx = productsData.findIndex(p => p.id === id);
        if (idx !== -1) {
            productsData.splice(idx, 1);
            loadProductsTable();
            loadCategoriesTable();
            showToast('Product deleted!');
        }
    }
}

// ========================================
// CATEGORIES PAGE
// ========================================

function loadCategoriesTable() {
    const tbody = document.getElementById('categoriesTableBody');
    tbody.innerHTML = '';
    
    categoriesData.forEach((category, index) => {
        const statusBadge = category.status === 'Active' 
            ? '<span class="st-badge active">Active</span>' 
            : '<span class="st-badge inactive">Inactive</span>';
        
        const totalProducts = productsData.filter(p => p.category === category.name).length;
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${category.code}</td>
            <td>${category.name}</td>
            <td>${category.description}</td>
            <td>${statusBadge}</td>
            <td>${totalProducts}</td>
            <td>
                <div class="st-action-btns">
                    <button class="btn-edit btn-small" onclick="editCategory(${category.id})">Edit</button>
                    <button class="btn-delete btn-small" onclick="deleteCategory(${category.id})">Delete</button>
                </div>
            </td>
        `;
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
    document.getElementById('categoryModalTitle').textContent = 'Add Category';
    document.getElementById('categoryForm').reset();
    document.getElementById('categoryModal').classList.add('show');
}

function editCategory(id) {
    const category = categoriesData.find(c => c.id === id);
    if (!category) return;
    
    editingCategoryId = id;
    document.getElementById('categoryModalTitle').textContent = 'Edit Category';
    document.getElementById('categoryCode').value = category.code;
    document.getElementById('categoryName').value = category.name;
    document.getElementById('categoryDesc').value = category.description;
    document.getElementById('categoryStatus').value = category.status;
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
        const newCategory = { id: Math.max(...categoriesData.map(c => c.id), 0) + 1, ...category };
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

function deleteCategory(id) {
    const category = categoriesData.find(c => c.id === id);
    if (!category) return;
    
    const productsInCategory = productsData.filter(p => p.category === category.name).length;
    if (productsInCategory > 0) {
        showToast(`Cannot delete category with ${productsInCategory} product(s)!`);
        return;
    }
    
    if (confirm('Are you sure you want to delete this category?')) {
        const idx = categoriesData.findIndex(c => c.id === id);
        if (idx !== -1) {
            categoriesData.splice(idx, 1);
            loadCategoriesTable();
            showToast('Category deleted!');
        }
    }
}

// ========================================
// CUSTOMERS & GROUPS PAGE
// ========================================

function loadCustomersTable() {
    const tbody = document.getElementById('customersTableBody');
    tbody.innerHTML = '';
    
    customersData.forEach((customer, index) => {
        const statusBadge = customer.status === 'Active' 
            ? '<span class="st-badge active">Active</span>' 
            : '<span class="st-badge inactive">Inactive</span>';
        
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
    document.getElementById('customerForm').reset();
    populateCustomerGroupSelect();
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
    const customer = customersData.find(c => c.id === id);
    if (!customer) return;
    
    editingCustomerId = id;
    document.getElementById('customerModalTitle').textContent = 'Edit Customer';
    document.getElementById('customerCode').value = customer.code;
    document.getElementById('customerName').value = customer.name;
    document.getElementById('customerPhone').value = customer.phone;
    document.getElementById('customerTag').value = customer.tag;
    populateCustomerGroupSelect();
    document.getElementById('customerGroup').value = customer.group;
    document.getElementById('customerStatus').value = customer.status;
    document.getElementById('customerNotes').value = customer.notes;
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
        const newCustomer = { id: Math.max(...customersData.map(c => c.id), 0) + 1, ...customer };
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
        const statusBadge = group.status === 'Active' 
            ? '<span class="st-badge active">Active</span>' 
            : '<span class="st-badge inactive">Inactive</span>';
        
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
    document.getElementById('groupForm').reset();
    document.getElementById('groupModal').classList.add('show');
}

function editGroup(id) {
    const group = groupsData.find(g => g.id === id);
    if (!group) return;
    
    editingGroupId = id;
    document.getElementById('groupModalTitle').textContent = 'Edit Group';
    document.getElementById('groupCode').value = group.code;
    document.getElementById('groupName').value = group.name;
    document.getElementById('groupDesc').value = group.description;
    document.getElementById('groupStatus').value = group.status;
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
        const newGroup = { id: Math.max(...groupsData.map(g => g.id), 0) + 1, ...group };
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

// ========================================
// WHATSAPP BROADCAST PAGE
// ========================================

function loadBroadcastPage() {
    setupBroadcastTabs();
    loadBroadcastContactList();
    populateBroadcastGroupSelect();
    updateAllContactsCount();
    loadBroadcastHistory();
}

function setupBroadcastTabs() {
    document.querySelectorAll('.st-recipient-tab').forEach(btn => {
        btn.addEventListener('click', (e) => {
            document.querySelectorAll('.st-recipient-tab').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.st-recipient-panel').forEach(p => p.classList.remove('active'));
            
            btn.classList.add('active');
            const recipientType = btn.dataset.recipient;
            document.getElementById(recipientType + '-recipients').classList.add('active');
        });
    });
}

function loadBroadcastContactList() {
    const list = document.getElementById('contactSelectionList');
    list.innerHTML = '';
    
    customersData.forEach(customer => {
        const div = document.createElement('div');
        div.className = 'st-contact-checkbox-item';
        div.innerHTML = `
            <label>
                <input type="checkbox" value="${customer.id}" onchange="updateSelectedContactsCount()">
                <span>${customer.name} (${customer.phone})</span>
            </label>
        `;
        list.appendChild(div);
    });
    
    const searchInput = document.getElementById('contactSearchBroadcast');
    if (searchInput) {
        searchInput.addEventListener('keyup', () => {
            const searchTerm = searchInput.value.toLowerCase();
            document.querySelectorAll('.st-contact-checkbox-item').forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }
}

function populateBroadcastGroupSelect() {
    const select = document.getElementById('broadcastGroupSelect');
    select.innerHTML = '<option value="">-- Select Group --</option>';
    groupsData.forEach(group => {
        const option = document.createElement('option');
        option.value = group.name;
        option.textContent = `${group.name} (${customersData.filter(c => c.group === group.name).length} members)`;
        select.appendChild(option);
    });
    
    select.addEventListener('change', () => {
        const groupName = select.value;
        const groupInfo = document.getElementById('groupInfo');
        if (groupName) {
            const members = customersData.filter(c => c.group === groupName).length;
            groupInfo.innerHTML = `<p>📢 <strong>${groupName}</strong></p><p>Total Members: <strong>${members}</strong></p>`;
        } else {
            groupInfo.innerHTML = '';
        }
    });
}

function updateAllContactsCount() {
    document.getElementById('allContactsCount').textContent = customersData.length;
}

function updateSelectedContactsCount() {
    const checked = document.querySelectorAll('#contactSelectionList input[type="checkbox"]:checked').length;
    document.getElementById('selectedContactsCount').textContent = checked;
    selectedCustomersForBroadcast = Array.from(document.querySelectorAll('#contactSelectionList input[type="checkbox"]:checked')).map(cb => parseInt(cb.value));
}

function previewBroadcastImage(event) {
    const file = event.target.files[0];
    if (file) {
        broadcastImageFile = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            document.getElementById('previewImage').innerHTML = `<img src="${e.target.result}" alt="preview">`;
        };
        reader.readAsDataURL(file);
    }
}

function previewBroadcast() {
    const title = document.getElementById('broadcastTitle').value || 'Untitled Broadcast';
    const message = document.getElementById('broadcastMessage').value || 'No message';
    const recipientTab = document.querySelector('.st-recipient-tab.active').dataset.recipient;
    
    let recipientText = 'None selected';
    if (recipientTab === 'all') {
        recipientText = `All ${customersData.length} contacts`;
    } else if (recipientTab === 'group') {
        const groupName = document.getElementById('broadcastGroupSelect').value;
        const count = customersData.filter(c => c.group === groupName).length;
        recipientText = groupName ? `${groupName} (${count} members)` : 'None selected';
    } else if (recipientTab === 'selected') {
        recipientText = `${selectedCustomersForBroadcast.length} selected contact(s)`;
    }
    
    document.getElementById('previewText').textContent = message;
    document.getElementById('previewTitle').textContent = title;
    document.getElementById('previewRecipients').textContent = recipientText;
    
    showToast('Preview updated!');
}

function saveBroadcastDraft() {
    const title = document.getElementById('broadcastTitle').value;
    if (!title) {
        showToast('Please enter a broadcast title!');
        return;
    }
    
    const newBroadcast = {
        id: Math.max(...broadcastHistoryData.map(b => b.id), 0) + 1,
        date: new Date().toLocaleString('id-ID'),
        title: title,
        recipientType: 'Draft',
        recipients: 0,
        attachment: broadcastImageFile ? 'Image' : 'No',
        status: 'Draft',
        createdBy: 'Admin'
    };
    
    broadcastHistoryData.push(newBroadcast);
    loadBroadcastHistory();
    showToast('Broadcast saved as draft!');
}

function sendBroadcast() {
    const title = document.getElementById('broadcastTitle').value;
    const message = document.getElementById('broadcastMessage').value;
    
    if (!title) {
        showToast('Please enter a broadcast title!');
        return;
    }
    
    if (!message) {
        showToast('Please enter a message!');
        return;
    }
    
    const recipientTab = document.querySelector('.st-recipient-tab.active').dataset.recipient;
    let recipientType = 'Unknown';
    let recipientCount = 0;
    
    if (recipientTab === 'all') {
        recipientType = 'All Contacts';
        recipientCount = customersData.length;
    } else if (recipientTab === 'group') {
        const groupName = document.getElementById('broadcastGroupSelect').value;
        if (!groupName) {
            showToast('Please select a group!');
            return;
        }
        recipientType = 'By Group';
        recipientCount = customersData.filter(c => c.group === groupName).length;
    } else if (recipientTab === 'selected') {
        if (selectedCustomersForBroadcast.length === 0) {
            showToast('Please select at least one contact!');
            return;
        }
        recipientType = 'Selected';
        recipientCount = selectedCustomersForBroadcast.length;
    }
    
    const newBroadcast = {
        id: Math.max(...broadcastHistoryData.map(b => b.id), 0) + 1,
        date: new Date().toLocaleString('id-ID'),
        title: title,
        recipientType: recipientType,
        recipients: recipientCount,
        attachment: broadcastImageFile ? 'Image' : 'No',
        status: 'Sent',
        createdBy: 'Admin'
    };
    
    broadcastHistoryData.push(newBroadcast);
    loadBroadcastHistory();
    resetBroadcastForm();
    showToast(`✅ Broadcast sent to ${recipientCount} recipient(s)!`);
}

function resetBroadcastForm() {
    document.getElementById('broadcastTitle').value = '';
    document.getElementById('broadcastMessage').value = '';
    document.getElementById('broadcastImage').value = '';
    document.getElementById('broadcastNotes').value = '';
    document.getElementById('previewImage').innerHTML = '';
    document.getElementById('previewText').textContent = 'Your message will appear here...';
    document.getElementById('previewTitle').textContent = '-';
    document.getElementById('previewRecipients').textContent = 'None selected';
    broadcastImageFile = null;
    selectedCustomersForBroadcast = [];
    document.querySelectorAll('#contactSelectionList input[type="checkbox"]').forEach(cb => cb.checked = false);
    updateSelectedContactsCount();
}

function loadBroadcastHistory() {
    const tbody = document.getElementById('broadcastHistoryBody');
    tbody.innerHTML = '';
    
    broadcastHistoryData.forEach((broadcast, index) => {
        const statusBadge = broadcast.status === 'Sent' 
            ? '<span class="st-badge active">Sent</span>'
            : '<span class="st-badge inactive">Draft</span>';
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${broadcast.date}</td>
            <td>${broadcast.title}</td>
            <td>${broadcast.recipientType}</td>
            <td>${broadcast.recipients}</td>
            <td>${broadcast.attachment}</td>
            <td>${statusBadge}</td>
            <td>${broadcast.createdBy}</td>
            <td><button class="btn-view btn-small" onclick="viewBroadcast(${broadcast.id})">View</button></td>
        `;
        tbody.appendChild(row);
    });
}

function viewBroadcast(id) {
    const broadcast = broadcastHistoryData.find(b => b.id === id);
    if (broadcast) {
        alert(`Broadcast: ${broadcast.title}\nStatus: ${broadcast.status}\nRecipients: ${broadcast.recipients}\nDate: ${broadcast.date}`);
    }
}

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
    populateStockProductSelect();
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

// ========================================
// SALES PAGE - POS SYSTEM
// ========================================

function loadSalesPage() {
    loadSalesProductSelect();
    renderSalesItems();
    loadSalesHistory();
}

function loadSalesProductSelect() {
    const select = document.getElementById('productSelect');
    select.innerHTML = '<option value="">-- Select Product --</option>';
    productsData.filter(p => p.status === 'Active' && p.stock > 0).forEach(product => {
        const option = document.createElement('option');
        option.value = product.id;
        option.textContent = `${product.name} (Rp ${product.price.toLocaleString('id-ID')})`;
        select.appendChild(option);
    });
}

function addSalesItem() {
    const productId = parseInt(document.getElementById('productSelect').value);
    const qty = parseInt(document.getElementById('qtyInput').value) || 1;
    
    if (!productId) {
        showToast('Please select a product!');
        return;
    }
    
    const product = productsData.find(p => p.id === productId);
    if (!product || product.stock < qty) {
        showToast('Insufficient stock!');
        return;
    }
    
    // Check if item already in cart
    const existingItem = currentCart.find(item => item.productId === productId);
    if (existingItem) {
        existingItem.qty += qty;
    } else {
        currentCart.push({
            productId: productId,
            name: product.name,
            price: product.price,
            qty: qty
        });
    }
    
    document.getElementById('productSelect').value = '';
    document.getElementById('qtyInput').value = '1';
    renderSalesItems();
    updatePaymentSummary();
}

function renderSalesItems() {
    const tbody = document.getElementById('salesItemsBody');
    tbody.innerHTML = '';
    
    currentCart.forEach((item, index) => {
        const subtotal = item.price * item.qty;
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${item.name}</td>
            <td><input type="number" value="${item.qty}" min="1" onchange="updateItemQty(${index}, this.value)" style="width: 50px; padding: 5px;"></td>
            <td>Rp ${item.price.toLocaleString('id-ID')}</td>
            <td>Rp ${subtotal.toLocaleString('id-ID')}</td>
            <td><button class="btn-delete btn-small" onclick="removeCartItem(${index})">Remove</button></td>
        `;
        tbody.appendChild(row);
    });
}

function updateItemQty(index, newQty) {
    const qty = parseInt(newQty) || 1;
    if (qty > 0) {
        currentCart[index].qty = qty;
        renderSalesItems();
        updatePaymentSummary();
    }
}

function removeCartItem(index) {
    currentCart.splice(index, 1);
    renderSalesItems();
    updatePaymentSummary();
}

function updatePaymentSummary() {
    const subtotal = currentCart.reduce((sum, item) => sum + (item.price * item.qty), 0);
    const discount = parseInt(document.getElementById('discountInput').value) || 0;
    const total = subtotal - discount;
    const payment = parseInt(document.getElementById('paymentReceived').value) || 0;
    const change = payment - total;
    
    document.getElementById('subtotalDisplay').textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
    document.getElementById('totalDisplay').textContent = `Rp ${total.toLocaleString('id-ID')}`;
    document.getElementById('changeDisplay').textContent = `Rp ${Math.max(0, change).toLocaleString('id-ID')}`;
}

document.getElementById('discountInput')?.addEventListener('change', updatePaymentSummary);
document.getElementById('paymentReceived')?.addEventListener('keyup', updatePaymentSummary);

function saveTransaction() {
    if (currentCart.length === 0) {
        showToast('Please add items to transaction!');
        return;
    }
    
    const subtotal = currentCart.reduce((sum, item) => sum + (item.price * item.qty), 0);
    const discount = parseInt(document.getElementById('discountInput').value) || 0;
    const total = subtotal - discount;
    
    const newTransaction = {
        id: Math.max(...salesHistoryData.map(t => t.id), 0) + 1,
        date: new Date().toLocaleString('id-ID'),
        transNo: `TRX-${new Date().toISOString().split('T')[0].replace(/-/g, '')}-${String(salesHistoryData.length + 1).padStart(3, '0')}`,
        items: currentCart.length,
        total: total,
        method: document.getElementById('paymentMethod').value,
        cashier: 'Admin',
        status: 'Completed'
    };
    
    salesHistoryData.push(newTransaction);
    
    // Update product stock
    currentCart.forEach(item => {
        const product = productsData.find(p => p.id === item.productId);
        if (product) {
            product.stock -= item.qty;
        }
    });
    
    clearTransaction();
    loadSalesHistory();
    showToast('Transaction saved successfully!');
}

function loadSalesHistory() {
    const tbody = document.getElementById('salesHistoryBody');
    tbody.innerHTML = '';
    
    salesHistoryData.forEach((trans, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${trans.date}</td>
            <td>${trans.transNo}</td>
            <td>${trans.items}</td>
            <td>Rp ${trans.total.toLocaleString('id-ID')}</td>
            <td>${trans.method}</td>
            <td>${trans.cashier}</td>
            <td><span class="st-badge active">${trans.status}</span></td>
            <td><button class="btn-view btn-small" onclick="viewTransaction(${trans.id})">View</button></td>
        `;
        tbody.appendChild(row);
    });
}

function printReceipt() {
    if (currentCart.length === 0) {
        showToast('No items to print!');
        return;
    }
    
    const subtotal = currentCart.reduce((sum, item) => sum + (item.price * item.qty), 0);
    const discount = parseInt(document.getElementById('discountInput').value) || 0;
    const total = subtotal - discount;
    
    let receipt = '=== SWEET TREATS DONUT SHOP ===\n\n';
    receipt += 'RECEIPT\n';
    receipt += new Date().toLocaleString() + '\n';
    receipt += '==============================\n';
    receipt += 'ITEMS:\n';
    
    currentCart.forEach(item => {
        receipt += `${item.name} x${item.qty}\n`;
        receipt += `Rp ${(item.price * item.qty).toLocaleString('id-ID')}\n`;
    });
    
    receipt += '==============================\n';
    receipt += `Subtotal: Rp ${subtotal.toLocaleString('id-ID')}\n`;
    receipt += `Discount: Rp ${discount.toLocaleString('id-ID')}\n`;
    receipt += `TOTAL: Rp ${total.toLocaleString('id-ID')}\n`;
    receipt += `Payment: ${document.getElementById('paymentMethod').value}\n`;
    receipt += '==============================\n';
    receipt += 'Thank you for your purchase!\n';
    
    alert(receipt);
    showToast('Receipt printed!');
}

function clearTransaction() {
    currentCart = [];
    document.getElementById('productSelect').value = '';
    document.getElementById('qtyInput').value = '1';
    document.getElementById('discountInput').value = '0';
    document.getElementById('paymentReceived').value = '';
    renderSalesItems();
    updatePaymentSummary();
}

function viewTransaction(id) {
    const trans = salesHistoryData.find(t => t.id === id);
    if (trans) {
        alert(`Transaction: ${trans.transNo}\nDate: ${trans.date}\nTotal: Rp ${trans.total.toLocaleString('id-ID')}\nMethod: ${trans.method}`);
    }
}

// ========================================
// REPORTS PAGE
// ========================================

function loadReportsData() {
    setupReportTabs();
    loadSalesReport();
    loadStockReport();
    loadProfitReport();
}

function setupReportTabs() {
    document.querySelectorAll('.st-tab-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const tabName = btn.dataset.tab;
            
            // Hide all tabs
            document.querySelectorAll('.st-tab-content').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.st-tab-btn').forEach(b => b.classList.remove('active'));
            
            // Show selected tab
            const tab = document.getElementById(tabName);
            if (tab) {
                tab.classList.add('active');
                btn.classList.add('active');
            }
        });
    });
}

function loadSalesReport() {
    const tbody = document.getElementById('salesReportBody');
    tbody.innerHTML = '';
    
    salesReportData.forEach((item, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${item.date}</td>
            <td>${item.transNo}</td>
            <td>${item.product}</td>
            <td>${item.qty}</td>
            <td>Rp ${item.price.toLocaleString('id-ID')}</td>
            <td>Rp ${item.cogs.toLocaleString('id-ID')}</td>
            <td>Rp ${item.total.toLocaleString('id-ID')}</td>
            <td>Rp ${item.profit.toLocaleString('id-ID')}</td>
        `;
        tbody.appendChild(row);
    });
}

function loadStockReport() {
    const tbody = document.getElementById('stockReportBody');
    tbody.innerHTML = '';
    
    productsData.forEach((product, index) => {
        const stockStatus = product.stock <= product.minStock ? 'Low Stock' : 'Safe';
        const statusBadge = product.stock <= product.minStock 
            ? '<span class="st-badge low">Low Stock</span>'
            : '<span class="st-badge safe">Safe Stock</span>';
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${product.code}</td>
            <td>${product.name}</td>
            <td>${product.category}</td>
            <td>${product.stock}</td>
            <td>${product.minStock}</td>
            <td>${statusBadge}</td>
        `;
        tbody.appendChild(row);
    });
}

function loadProfitReport() {
    const tbody = document.getElementById('profitReportBody');
    tbody.innerHTML = '';
    
    // Group sales by product
    const profitByProduct = {};
    salesReportData.forEach(item => {
        if (!profitByProduct[item.product]) {
            const product = productsData.find(p => p.name === item.product);
            profitByProduct[item.product] = {
                units: 0,
                revenue: 0,
                cogs: 0,
                profit: 0,
                cogs_per_unit: product?.cogs || 0
            };
        }
        profitByProduct[item.product].units += item.qty;
        profitByProduct[item.product].revenue += item.total;
        profitByProduct[item.product].cogs += item.qty * item.cogs;
        profitByProduct[item.product].profit += item.profit;
    });
    
    let index = 1;
    Object.entries(profitByProduct).forEach(([product, data]) => {
        const margin = data.revenue > 0 ? ((data.profit / data.revenue) * 100).toFixed(1) : 0;
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${index++}</td>
            <td>${product}</td>
            <td>${data.units}</td>
            <td>Rp ${data.revenue.toLocaleString('id-ID')}</td>
            <td>Rp ${data.cogs.toLocaleString('id-ID')}</td>
            <td>Rp ${data.profit.toLocaleString('id-ID')}</td>
            <td>${margin}%</td>
        `;
        tbody.appendChild(row);
    });
}

// ========================================
// UTILITY FUNCTIONS
// ========================================

function showToast(message) {
    const st_toast = document.getElementById('st-toast');
    st_toast.textContent = message;
    st_toast.classList.add('show');
    
    setTimeout(() => {
        st_toast.classList.remove('show');
    }, 3000);
}

function exportCSV() {
    showToast('CSV export initiated!');
    // In a real app, this would generate CSV
}

function printReport() {
    showToast('Report sent to printer!');
    window.print();
}

// ========================================
// INITIALIZE APP
// ========================================

// Setup tab switching for customer page
function setupTabSwitching() {
    document.querySelectorAll('.st-tab-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const tabName = btn.dataset.tab;
            
            document.querySelectorAll('.st-tab-content').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.st-tab-btn').forEach(b => b.classList.remove('active'));
            
            const tab = document.getElementById(tabName);
            if (tab) {
                tab.classList.add('active');
                btn.classList.add('active');
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    // Initial load
    loadProductsTable();
    loadCategoriesTable();
    loadSalesProductSelect();
    populateStockProductSelect();
    populateProductCategorySelect();
    setupTabSwitching();
    
    // Close modals when clicking outside
    document.getElementById('productModal')?.addEventListener('click', (e) => {
        if (e.target.id === 'productModal') closeProductModal();
    });
    
    document.getElementById('stockModal')?.addEventListener('click', (e) => {
        if (e.target.id === 'stockModal') closeStockModal();
    });
    
    document.getElementById('categoryModal')?.addEventListener('click', (e) => {
        if (e.target.id === 'categoryModal') closeCategoryModal();
    });
    
    document.getElementById('customerModal')?.addEventListener('click', (e) => {
        if (e.target.id === 'customerModal') closeCustomerModal();
    });
    
    document.getElementById('groupModal')?.addEventListener('click', (e) => {
        if (e.target.id === 'groupModal') closeGroupModal();
    });
});