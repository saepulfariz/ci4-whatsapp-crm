// ========================================
// Stock & Sales Management - JavaScript
// Application: Sweet Treats Donut Shop
// ========================================

// ========================================
// DUMMY DATA - Static Product & Transaction Data
// ========================================

// Category Master Data


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

            const submenuId = btn.dataset.submenu;
            const submenu = document.getElementById(submenuId);

            if (!submenu) return;

            btn.classList.toggle('open');
            submenu.classList.toggle('open');
        });

    } else {

        btn.addEventListener('click', (e) => {

            const page = btn.dataset.page;
            if (page) navigateToPage(page);

            document.querySelectorAll('.st-nav-item, .st-nav-subitem')
                .forEach(item => item.classList.remove('active'));

            btn.classList.add('active');

        });

    }
});

document.querySelectorAll('.st-nav-subitem').forEach(btn => {

    btn.addEventListener('click', (e) => {

        const page = btn.dataset.page;
        if (page) navigateToPage(page);

        document.querySelectorAll('.st-nav-item, .st-nav-subitem')
            .forEach(item => item.classList.remove('active'));

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
            // loadProductsTable();
        } else if (pageName === 'master-category') {
            // loadCategoriesTable();
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
// SALES PAGE - POS SYSTEM
// ========================================

function loadSalesPage() {
    // loadSalesProductSelect();
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
    // loadProductsTable();
    // loadCategoriesTable();
    // loadSalesProductSelect();
    // populateStockProductSelect();
    // populateProductCategorySelect();
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